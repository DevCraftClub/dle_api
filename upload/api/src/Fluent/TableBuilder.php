<?php

declare(strict_types=1);

namespace DleApi\Fluent;

use DleApi\Schema\SchemaRegistry;
use DleApi\Schema\TableSchemaInterface;

/**
 * Универсальный Fluent-билдер для любой таблицы SchemaRegistry.
 */
final class TableBuilder {
	private TableSchemaInterface $schema;

	/** @var array<string, mixed> */
	private array $attrs = [];

	/** @var array<string, list<TableBuilder>> */
	private array $children = [];

	public function __construct(private string $table) {
		$this->schema = SchemaRegistry::get($table);
		$this->attrs  = $this->schema->defaults();
		$pk           = $this->schema->primaryKey();
		foreach(is_array($pk) ? $pk : [$pk] as $key) {
			unset($this->attrs[$key]);
		}
	}

	public function table(): string {
		return $this->table;
	}

	/**
	 * @param array<string, mixed> $attrs
	 */
	public function withAttributes(array $attrs): self {
		$this->attrs = array_merge($this->attrs, $this->schema->showColumns($attrs));

		return $this;
	}

	/**
	 * Колонка или nested child table.
	 *
	 * @param mixed $value скаляр | TableBuilder | list<TableBuilder|array>
	 */
	public function with(string $name, mixed $value): self {
		if($value instanceof self || (is_array($value) && $this->isBuilderList($value))) {
			return $this->withChild($name, $value);
		}
		if(is_array($value) && $this->isAssoc($value) && RelationMap::nestedFk($this->table, $name) !== null) {
			return $this->withChild($name, prepare($name)->withAttributes($value));
		}
		if(!in_array($name, $this->schema->columns(), true)) {
			throw new \InvalidArgumentException("Неизвестная колонка/связь «{$name}» для таблицы {$this->table}");
		}
		if(is_array($value) && array_is_list($value)) {
			$value = implode(',', array_map(static fn($v) => (string) $v, $value));
		}
		$this->attrs[$name] = $value;

		return $this;
	}

	/**
	 * @param TableBuilder|list<TableBuilder|array<string, mixed>> $child
	 */
	public function withChild(string $childTable, TableBuilder|array $child): self {
		$fk = RelationMap::nestedFk($this->table, $childTable);
		if($fk === null) {
			throw new \InvalidArgumentException("Нет nested-связи {$this->table} → {$childTable} в RelationMap");
		}
		$builders = [];
		if($child instanceof self) {
			$builders[] = $child;
		} else {
			foreach($child as $item) {
				if($item instanceof self) {
					$builders[] = $item;
				} elseif(is_array($item)) {
					$builders[] = prepare($childTable)->withAttributes($item);
				} else {
					throw new \InvalidArgumentException('Ожидался TableBuilder или array attrs');
				}
			}
		}
		if(!isset($this->children[$childTable])) {
			$this->children[$childTable] = [];
		}
		foreach($builders as $b) {
			$this->children[$childTable][] = $b;
		}

		return $this;
	}

	/**
	 * Magic: withTitle('x') → with('title', 'x'); withAllowComm(1) → allow_comm.
	 *
	 * @param list<mixed> $arguments
	 */
	public function __call(string $name, array $arguments): self {
		$col = \DleApi\Schema\WithColumnMagicTrait::withMethodToColumn($name);
		if($arguments === []) {
			throw new \InvalidArgumentException(__('Метод требует значение') . ": {$name}");
		}

		return $this->with($col, $arguments[0]);
	}

	/**
	 * Принудительно выставить колонку (для FK от родителя).
	 */
	public function setColumn(string $column, mixed $value): self {
		$this->attrs[$column] = $value;

		return $this;
	}

	/**
	 * @throws \RuntimeException
	 */
	public function create(): int {
		$row = $this->schema->showColumns($this->attrs);
		$id  = dle_api_insert($this->table, $row);
		if($id < 1 && !$this->hasCompositeOrNoAiPk()) {
			// некоторые таблицы могут вернуть 0 — проверим PK в attrs
			$pk = $this->schema->primaryKey();
			if(is_string($pk) && isset($row[$pk])) {
				$id = (int) $row[$pk];
			}
		}
		if($id < 1) {
			throw new \RuntimeException("Не удалось создать запись в {$this->table}");
		}

		foreach($this->children as $childTable => $builders) {
			$fk = RelationMap::nestedFk($this->table, $childTable);
			if($fk === null) {
				continue;
			}
			foreach($builders as $builder) {
				$builder->setColumn($fk, $id)->create();
			}
		}

		return $id;
	}

	private function hasCompositeOrNoAiPk(): bool {
		$pk = $this->schema->primaryKey();

		return is_array($pk);
	}

	/** @param list<mixed> $value */
	private function isBuilderList(array $value): bool {
		if($value === [] || $this->isAssoc($value)) {
			return false;
		}
		foreach($value as $item) {
			if(!($item instanceof self) && !is_array($item)) {
				return false;
			}
		}

		return true;
	}

	/** @param array<mixed> $arr */
	private function isAssoc(array $arr): bool {
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
}
