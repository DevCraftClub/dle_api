<?php

declare(strict_types=1);

namespace DleApi\Sdk\Presenter;

use DleApi\Sdk\Model\XfieldCatalogModel;

/**
 * Презентер изменения каталога доп. полей (post|user).
 */
final class XfieldPresenter extends AbstractConfigPresenter {
	private XfieldCatalogModel $model;

	/** @var array<string, array<string, mixed>> */
	private array $upsertFields = [];

	/** @var list<string> */
	private array $removeFields = [];

	/** @var array<string, array<string, mixed>> */
	private array $upsertGroups = [];

	/** @var list<string> */
	private array $removeGroups = [];

	public function __construct(string $scope = 'post') {
		$this->model = new XfieldCatalogModel($scope);
	}

	public function model(): XfieldCatalogModel {
		return $this->model;
	}

	/**
	 * @param array<string, mixed> $def
	 */
	public function upsert(string $name, array $def): static {
		$def['name']                 = $name;
		$this->upsertFields[$name]   = $def;
		$this->removeFields          = array_values(array_filter(
			$this->removeFields,
			static fn(string $n): bool => $n !== $name
		));

		return $this;
	}

	public function remove(string $name): static {
		unset($this->upsertFields[$name]);
		if(!in_array($name, $this->removeFields, true)) {
			$this->removeFields[] = $name;
		}

		return $this;
	}

	/**
	 * Группа доп. полей (только scope=post).
	 *
	 * @param array<string, mixed> $def
	 */
	public function setGroup(string $name, array $def): static {
		$this->upsertGroups[$name] = $def;
		$this->removeGroups        = array_values(array_filter(
			$this->removeGroups,
			static fn(string $n): bool => $n !== $name
		));

		return $this;
	}

	/**
	 * Удалить группу (только scope=post).
	 */
	public function removeGroup(string $name): static {
		unset($this->upsertGroups[$name]);
		if(!in_array($name, $this->removeGroups, true)) {
			$this->removeGroups[] = $name;
		}

		return $this;
	}

	public function save(): static {
		$store = $this->model->store();
		$norm  = new \DleApi\Xfield\XfieldDefinitionNormalizer($store);
		foreach($this->removeFields as $name) {
			$store->deleteField($name);
		}
		foreach($this->upsertFields as $name => $def) {
			$def['name'] = $name;
			$store->upsertField($name, $norm->normalize($def, requireUniqueName: false));
		}
		foreach($this->removeGroups as $name) {
			$store->deleteGroup($name);
		}
		foreach($this->upsertGroups as $name => $def) {
			$store->upsertGroup($name, $def);
		}
		$this->upsertFields = [];
		$this->removeFields = [];
		$this->upsertGroups = [];
		$this->removeGroups = [];

		return $this;
	}
}
