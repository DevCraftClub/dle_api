<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Repositories;

use DevCraft\Core\Abstracts\AbstractRepository;
use DevCraft\Modules\DleApi\Models\ApiKey;

/**
 * Репозиторий API-ключей.
 */
final class ApiKeyRepository extends AbstractRepository {

	/**
	 * @return list<ApiKey>
	 */
	public function all(): array {
		/** @var list<ApiKey> $rows */
		$rows = $this->select()->orderBy('id', 'DESC')->fetchAll();

		return $rows;
	}

	/**
	 * @param array<string, mixed> $data
	 */
	public function create(array $data): ApiKey {
		$entity           = new ApiKey();
		$entity->api      = (string) $data['api'];
		$entity->is_admin = !empty($data['is_admin']);
		$entity->active   = array_key_exists('active', $data) ? !empty($data['active']) : true;
		$entity->user_id  = (int) ($data['user_id'] ?? 0);
		$entity->own_only = array_key_exists('own_only', $data) ? !empty($data['own_only']) : true;
		$entity->access_level_id = (int) ($data['access_level_id'] ?? 0);

		if(isset($data['creator'])) {
			$entity->setCreator((int) $data['creator']);
		}

		/** @var ApiKey $saved */
		$saved = $this->saveEntity($entity);

		return $saved;
	}

	public function find(int $id): ?ApiKey {
		/** @var ApiKey|null $entity */
		$entity = $this->findByPK($id);

		return $entity;
	}

	public function findActive(int $id): ?ApiKey {
		$key = $this->find($id);
		if($key === null || !$key->active) {
			return null;
		}

		return $key;
	}

	/**
	 * Активный ключ по значению `api`.
	 */
	public function findActiveByApi(string $api): ?ApiKey {
		if($api === '') {
			return null;
		}

		/** @var ApiKey|null $key */
		$key = $this->select()->where('api', $api)->fetchOne();
		if($key === null || !$key->active) {
			return null;
		}

		return $key;
	}

	/**
	 * Активный ключ пользователя (последний по id).
	 */
	public function findActiveByUserId(int $userId): ?ApiKey {
		if($userId < 1) {
			return null;
		}

		/** @var ApiKey|null $key */
		$key = $this->select()
			->where('user_id', $userId)
			->where('active', true)
			->orderBy('id', 'DESC')
			->fetchOne();

		return $key;
	}

	/**
	 * Гостевой ключ (user_id = 0).
	 */
	public function findActiveGuest(): ?ApiKey {
		/** @var ApiKey|null $key */
		$key = $this->select()
			->where('user_id', 0)
			->where('active', true)
			->orderBy('id', 'DESC')
			->fetchOne();

		return $key;
	}

	/**
	 * Массив для HTTP-атрибута api_key.
	 *
	 * @return array{id: int, api: string, is_admin: bool, active: bool, user_id: int, own_only: bool, access_level_id: int}
	 */
	public static function toAuthArray(ApiKey $key): array {
		return [
			'id'               => $key->id(),
			'api'              => $key->api,
			'is_admin'         => $key->is_admin,
			'active'           => $key->active,
			'user_id'          => $key->user_id,
			'own_only'         => $key->own_only,
			'access_level_id'  => $key->access_level_id ?? 0,
		];
	}

	public function setActive(int $id, bool $active): void {
		$entity = $this->find($id);

		if($entity === null) {
			return;
		}

		$entity->active = $active;
		$this->saveEntity($entity);
	}

	public function delete(int $id): void {
		$entity = $this->find($id);

		if($entity !== null) {
			$this->deleteEntity($entity);
		}
	}

}
