<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Repositories;

use DevCraft\Core\Abstracts\AbstractRepository;
use DevCraft\Modules\DleApi\Models\ApiAccessLevel;

/**
 * Репозиторий уровней доступа.
 */
final class ApiAccessLevelRepository extends AbstractRepository {

	/**
	 * @return list<ApiAccessLevel>
	 */
	public function all(): array {
		/** @var list<ApiAccessLevel> $rows */
		$rows = $this->select()->orderBy('sort', 'ASC')->orderBy('id', 'ASC')->fetchAll();

		return $rows;
	}

	/**
	 * @return list<ApiAccessLevel>
	 */
	public function allActive(): array {
		/** @var list<ApiAccessLevel> $rows */
		$rows = $this->select()->where('active', true)->orderBy('sort', 'ASC')->fetchAll();

		return $rows;
	}

	public function find(int $id): ?ApiAccessLevel {
		/** @var ApiAccessLevel|null $e */
		$e = $this->findByPK($id);

		return $e;
	}

	/**
	 * @param array<string, mixed> $data
	 */
	public function create(array $data): ApiAccessLevel {
		$e               = new ApiAccessLevel();
		$e->name         = (string) ($data['name'] ?? '');
		$e->active       = array_key_exists('active', $data) ? !empty($data['active']) : true;
		$e->sort         = (int) ($data['sort'] ?? 0);
		$e->premoderate  = !empty($data['premoderate']);
		$e->own_only     = array_key_exists('own_only', $data) ? !empty($data['own_only']) : true;
		$e->cheater      = !empty($data['cheater']);
		$e->mask_ip      = array_key_exists('mask_ip', $data) ? !empty($data['mask_ip']) : true;
		$e->mask_passwords = array_key_exists('mask_passwords', $data) ? !empty($data['mask_passwords']) : true;
		$e->mask_personal  = array_key_exists('mask_personal', $data) ? !empty($data['mask_personal']) : true;
		/** @var ApiAccessLevel $saved */
		$saved = $this->saveEntity($e);

		return $saved;
	}

	/**
	 * @param array<string, mixed> $data
	 */
	public function update(ApiAccessLevel $e, array $data): ApiAccessLevel {
		if(array_key_exists('name', $data)) {
			$e->name = (string) $data['name'];
		}
		if(array_key_exists('active', $data)) {
			$e->active = !empty($data['active']);
		}
		if(array_key_exists('sort', $data)) {
			$e->sort = (int) $data['sort'];
		}
		if(array_key_exists('premoderate', $data)) {
			$e->premoderate = !empty($data['premoderate']);
		}
		if(array_key_exists('own_only', $data)) {
			$e->own_only = !empty($data['own_only']);
		}
		if(array_key_exists('cheater', $data)) {
			$e->cheater = !empty($data['cheater']);
		}
		if(array_key_exists('mask_ip', $data)) {
			$e->mask_ip = !empty($data['mask_ip']);
		}
		if(array_key_exists('mask_passwords', $data)) {
			$e->mask_passwords = !empty($data['mask_passwords']);
		}
		if(array_key_exists('mask_personal', $data)) {
			$e->mask_personal = !empty($data['mask_personal']);
		}
		/** @var ApiAccessLevel $saved */
		$saved = $this->saveEntity($e);

		return $saved;
	}

	public function delete(int $id): void {
		$e = $this->find($id);
		if($e !== null) {
			$this->deleteEntity($e);
		}
	}

}
