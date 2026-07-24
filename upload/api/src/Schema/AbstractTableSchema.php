<?php

declare(strict_types=1);

namespace DleApi\Schema;

use DleApi\Fluent\RelationMap;
use DleApi\Sdk\SdkException;

/**
 * Базовая реализация Schema таблицы DLE (+ fluent CRUD).
 */
abstract class AbstractTableSchema implements TableSchemaInterface {
	use WithColumnMagicTrait;
	/** @var array<string, mixed> значения колонок без public-свойств (introspected) */
	protected array $bag = [];

	/** @var array<string, list<AbstractTableSchema>> */
	protected array $childEntities = [];

	/** @return list<string> */
	abstract protected function columnList(): array;

	/** @return array<string, mixed> */
	abstract protected function defaultMap(): array;

	abstract public function table(): string;

	public function columns(): array {
		return $this->columnList();
	}

	public function defaults(): array {
		return $this->defaultMap();
	}

	/**
	 * @param array<string, mixed> $attrs
	 * @param list<string> $selectedColumns
	 * @param array<string, string> $order колонка => ASC|DESC
	 * @return list<static>
	 */
	public static function filter(
		array $attrs,
		array $selectedColumns = [],
		array $order = [],
		int $limit = 20,
		int $offset = 0,
	): array {
		$probe = new static();
		$table = $probe->table();
		$pk    = $probe->primaryKey();
		if($order === []) {
			$order = is_string($pk) ? [$pk => 'DESC'] : [];
		}
		$data = dle_api_list($table, $limit, $offset, $order, $selectedColumns, $attrs);

		return array_map(static fn(array $item) => static::fromArray($item), $data);
	}

	public function primaryKey(): string|array {
		return 'id';
	}

	/**
	 * @return list<array{from: string, column: string, to: string, toColumn: string, kind: string}>
	 */
	public function relations(): array {
		return RelationMap::edgesFrom($this->table());
	}

	/**
	 * Белый список атрибутов по колонкам схемы (бывший instance-filter).
	 *
	 * @param array<string, mixed> $attrs
	 * @return array<string, mixed>
	 */
	public function showColumns(array $attrs): array {
		$allowed = array_flip($this->columnList());
		$out     = [];
		foreach($attrs as $k => $v) {
			if(isset($allowed[$k])) {
				$out[$k] = $v;
			}
		}

		return $out;
	}

	public static function fromArray(array $data): static {
		$inst = new static();
		foreach($inst->columnList() as $col) {
			if(!array_key_exists($col, $data)) {
				continue;
			}
			$inst->writeColumn($col, $data[$col]);
		}

		return $inst;
	}

	public function with(string $attrName, mixed $value): static {
		$this->writeColumn($attrName, $value);

		return $this;
	}

	public function withAttributes(array $attributes): static {
		foreach($attributes as $k => $v) {
			if(in_array((string) $k, $this->columnList(), true)) {
				$this->writeColumn((string) $k, $v);
			}
		}

		return $this;
	}

	public function asArray(): array {
		$out = [];
		foreach($this->columnList() as $col) {
			$out[$col] = $this->readColumn($col);
		}

		return $out;
	}

	public function asJson(): string {
		return json_encode($this->asArray(), JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
	}

	public function create(): static {
		$row = $this->showColumns($this->asArray());
		$pk  = $this->primaryKey();
		$pks = is_array($pk) ? $pk : [$pk];
		foreach($pks as $key) {
			if(array_key_exists($key, $row) && ($row[$key] === null || $row[$key] === '' || $row[$key] === 0 || $row[$key] === '0')) {
				unset($row[$key]);
			}
		}
		$id = dle_api_insert($this->table(), $row);
		if(is_string($pk) && $id > 0) {
			$this->writeColumn($pk, $id);
		}
		$this->persistChildren();

		return $this;
	}

	public function save(): static {
		$pk = $this->primaryKey();
		if(!is_string($pk)) {
			throw SdkException::missingPrimaryKey('Составной PK: используйте create() или низкоуровневый API.');
		}
		$id = $this->readColumn($pk);
		if($id === null || $id === '' || $id === 0 || $id === '0') {
			return $this->create();
		}
		$set = $this->showColumns($this->asArray());
		unset($set[$pk]);
		dle_api_update_by_pk($this->table(), $id, $set);
		$this->persistChildren();

		return $this;
	}

	public function delete(): static {
		$pk = $this->primaryKey();
		if(!is_string($pk)) {
			throw SdkException::missingPrimaryKey('Составной PK не поддерживается в delete().');
		}
		$id = $this->readColumn($pk);
		if($id === null || $id === '' || $id === 0 || $id === '0') {
			throw SdkException::missingPrimaryKey('Укажите id через with()/fromArray() перед delete().');
		}
		dle_api_delete_by_pk($this->table(), $id);

		return $this;
	}

	/**
	 * Прикрепить дочернюю Schema-сущность (RelationMap nested).
	 */
	public function attachChildEntity(string $childTable, AbstractTableSchema $entity): static {
		$this->childEntities[$childTable][] = $entity;

		return $this;
	}

	protected function writeColumn(string $column, mixed $value): void {
		if(!in_array($column, $this->columnList(), true)) {
			throw SdkException::unknownColumn($this->table(), $column);
		}
		if(property_exists($this, $column)) {
			$current = $this->{$column};
			$this->{$column} = match (true) {
				is_int($current)   => (int) $value,
				is_float($current) => (float) $value,
				is_bool($current)  => (bool) $value,
				default            => $value,
			};
		} else {
			$this->bag[$column] = $value;
		}
	}

	protected function readColumn(string $column): mixed {
		if(property_exists($this, $column) && !array_key_exists($column, $this->bag)) {
			return $this->{$column};
		}
		if(array_key_exists($column, $this->bag)) {
			return $this->bag[$column];
		}
		$defaults = $this->defaultMap();

		return $defaults[$column] ?? null;
	}

	/** @deprecated использовать writeColumn */
	protected function updateColumn(string $column, mixed $value): void {
		$this->writeColumn($column, $value);
	}

	private function persistChildren(): void {
		$parentPk = $this->primaryKey();
		if(!is_string($parentPk)) {
			return;
		}
		$parentId = $this->readColumn($parentPk);
		if($parentId === null || $parentId === '' || $parentId === 0) {
			return;
		}
		foreach($this->childEntities as $childTable => $entities) {
			$fk = RelationMap::nestedFk($this->table(), $childTable);
			if($fk === null) {
				continue;
			}
			foreach($entities as $entity) {
				$entity->writeColumn($fk, $parentId);
				$entity->create();
			}
		}
	}
}
