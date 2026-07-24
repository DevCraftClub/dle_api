<?php

declare(strict_types=1);

namespace DleApi\Sdk\Model;

use DleApi\Schema\SchemaRegistry;
use DleApi\Schema\TableSchemaInterface;

/**
 * Модель строки таблицы для презентеров SDK.
 */
final class TableModel {
	/** @var array<string, mixed> */
	private array $attrs;

	public function __construct(
		private string $table,
		private ?int $id = null,
		array $attrs = [],
	) {
		$this->attrs = $attrs;
	}

	public function table(): string {
		return $this->table;
	}

	public function id(): ?int {
		return $this->id;
	}

	public function setId(?int $id): self {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function attrs(): array {
		return $this->attrs;
	}

	/**
	 * @param array<string, mixed> $attrs
	 */
	public function setAttrs(array $attrs): self {
		$this->attrs = $attrs;

		return $this;
	}

	public function schema(): TableSchemaInterface {
		return SchemaRegistry::get($this->table);
	}
}
