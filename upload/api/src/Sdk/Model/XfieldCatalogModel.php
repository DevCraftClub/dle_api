<?php

declare(strict_types=1);

namespace DleApi\Sdk\Model;

use DleApi\Xfield\XfieldStore;

/**
 * Модель каталога доп. полей (post|user) через XfieldStore.
 */
final class XfieldCatalogModel {
	private XfieldStore $store;

	public function __construct(private string $scope = 'post') {
		$this->store = new XfieldStore($scope);
	}

	public function scope(): string {
		return $this->scope;
	}

	public function store(): XfieldStore {
		return $this->store;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function catalog(): array {
		return $this->store->read();
	}

	/**
	 * @return array<string, mixed>|null
	 */
	public function field(string $name): ?array {
		return $this->store->getField($name);
	}

	/**
	 * @return array<string, mixed>|null
	 */
	public function group(string $name): ?array {
		return $this->store->getGroup($name);
	}
}
