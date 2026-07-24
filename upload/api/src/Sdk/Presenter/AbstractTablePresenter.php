<?php

declare(strict_types=1);

namespace DleApi\Sdk\Presenter;

use DleApi\Fluent\RelationMap;
use DleApi\Fluent\TableBuilder;
use DleApi\Schema\SchemaRegistry;
use DleApi\Schema\TableSchemaInterface;
use DleApi\Sdk\Model\TableModel;
use function DleApi\Fluent\prepare;

/**
 * Презентер создания строки таблицы через Fluent.
 */
abstract class AbstractTablePresenter extends AbstractPresenter {
	/** @var array<string, mixed> */
	protected array $attrs = [];

	/** @var array<string, mixed> */
	protected array $children = [];

	protected TableModel $model;

	public function __construct(?string $table = null) {
		$this->model = new TableModel($table ?? $this->table());
	}

	abstract public function table(): string;

	public function model(): TableModel {
		return $this->model;
	}

	public function schema(): TableSchemaInterface {
		return SchemaRegistry::get($this->table());
	}

	/**
	 * @param array<string, mixed> $attrs
	 */
	public function withAttributes(array $attrs): static {
		$this->attrs = array_merge($this->attrs, $attrs);
		$this->model->setAttrs($this->attrs);

		return $this;
	}

	public function with(string $name, mixed $value): static {
		if($value instanceof TableBuilder || RelationMap::nestedFk($this->table(), $name) !== null) {
			return $this->withChild($name, $value);
		}
		if(is_array($value) && ($value === [] || array_is_list($value)) && in_array($name, RelationMap::childrenOf($this->table()), true)) {
			return $this->withChild($name, $value);
		}
		$this->attrs[$name] = $value;
		$this->model->setAttrs($this->attrs);

		return $this;
	}

	/**
	 * Вложенная дочерняя таблица (RelationMap).
	 */
	protected function withChild(string $childTable, mixed $child): static {
		$this->children[$childTable] = $child;

		return $this;
	}

	/**
	 * Создаёт запись через Fluent prepare() и возвращает $this.
	 */
	public function create(): static {
		$builder = prepare($this->table());
		if($this->attrs !== []) {
			$builder->withAttributes($this->attrs);
		}
		foreach($this->children as $childTable => $payload) {
			$builder->withChild($childTable, $this->toChildPayload($childTable, $payload));
		}
		$id = $builder->create();
		$this->createdId = $id;
		$this->model->setId($id);
		$this->model->setAttrs($this->attrs);

		return $this;
	}

	/**
	 * @return TableBuilder|list<TableBuilder|array<string, mixed>>
	 */
	private function toChildPayload(string $childTable, mixed $payload): mixed {
		if($payload instanceof TableBuilder) {
			return $payload;
		}
		if(!is_array($payload)) {
			return prepare($childTable);
		}
		if($payload === [] || array_is_list($payload)) {
			$out = [];
			foreach($payload as $item) {
				if($item instanceof TableBuilder) {
					$out[] = $item;
				} elseif(is_array($item)) {
					$out[] = prepare($childTable)->withAttributes($item);
				}
			}

			return $out;
		}

		return prepare($childTable)->withAttributes($payload);
	}
}
