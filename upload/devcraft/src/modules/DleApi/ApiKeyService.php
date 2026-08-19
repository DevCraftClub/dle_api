<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Services;

use DevCraft\Core\Application;
use DevCraft\Modules\DleApi\Models\ApiKey;
use DevCraft\Modules\DleApi\Models\ApiScope;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRepository;
use DevCraft\Modules\DleApi\Repositories\ApiScopeRepository;
use RuntimeException;

/**
 * Создание и обновление API-ключей вместе с матрицей scope.
 */
final class ApiKeyService {

	/**
	 * @param array<string, mixed> $data
	 * @param array<string, array<string, mixed>> $tables
	 * @return array{id: int, api: string, is_admin: bool, active: bool, user_id: int, own_only: bool, access_level_id: int}
	 */
	public function create(array $data, array $tables, int $creatorId): array {
		/** @var ApiKeyRepository $repo */
		$repo = Application::instance()->database()->repository(ApiKey::class);

		$levelId = (int) ($data['access_level_id'] ?? 0);
		$flags   = $this->flagsFromLevel($levelId, $data);

		$api    = (new ApiKeyGenerator())->generate();
		$entity = $repo->create([
			'api'              => $api,
			'is_admin'         => $flags['is_admin'],
			'creator'          => $creatorId,
			'active'           => 1,
			'user_id'          => (int) ($data['user_id'] ?? 0),
			'own_only'         => $flags['own_only'],
			'access_level_id'  => $levelId,
		]);

		/** @var ApiScopeRepository $scopes */
		$scopes = Application::instance()->database()->repository(ApiScope::class);
		$scopes->replaceForKey($entity->id(), $tables);

		return $this->payload($entity);
	}

	/**
	 * @param array<string, mixed> $data
	 * @param array<string, array<string, mixed>> $tables
	 * @return array{id: int, api: string, is_admin: bool|int, active: bool|int, user_id: int, own_only: bool|int, access_level_id: int}
	 */
	public function update(int $id, array $data, array $tables): array {
		if($id < 1) {
			throw new RuntimeException('validation');
		}

		/** @var ApiKeyRepository $repo */
		$repo = Application::instance()->database()->repository(ApiKey::class);
		$key  = $repo->find($id);

		if($key === null) {
			throw new RuntimeException('not_found');
		}

		$levelId = array_key_exists('access_level_id', $data)
			? (int) $data['access_level_id']
			: $key->access_level_id;
		$flags = $this->flagsFromLevel($levelId, $data);

		$key->user_id         = (int) ($data['user_id'] ?? $key->user_id);
		$key->access_level_id = $levelId;
		$key->is_admin        = $flags['is_admin'];
		$key->own_only        = $flags['own_only'];

		$repo->saveEntity($key);

		/** @var ApiScopeRepository $scopes */
		$scopes = Application::instance()->database()->repository(ApiScope::class);
		$scopes->replaceForKey($id, $tables);

		return $this->payload($key);
	}

	/**
	 * @param array<string, mixed> $data
	 * @return array{is_admin: bool, own_only: bool}
	 */
	private function flagsFromLevel(int $levelId, array $data): array {
		if($levelId > 0) {
			$level = (new AccessLevelResolver())->findActive($levelId);
			if($level !== null) {
				return [
					'is_admin' => $level->cheater,
					'own_only' => $level->own_only && !$level->cheater,
				];
			}
		}

		return [
			'is_admin' => !empty($data['is_admin']),
			'own_only' => array_key_exists('own_only', $data) ? !empty($data['own_only']) : true,
		];
	}

	/**
	 * @return array{
	 *     id: int,
	 *     api: string,
	 *     is_admin: bool|int,
	 *     active: bool|int,
	 *     user_id: int,
	 *     own_only: bool|int,
	 *     access_level_id: int,
	 *     level_name: string,
	 *     user_label: string
	 * }
	 */
	private function payload(ApiKey $key): array {
		return [
			'id'              => $key->id(),
			'api'             => $key->api,
			'is_admin'        => $key->is_admin,
			'active'          => $key->active,
			'user_id'         => $key->user_id,
			'own_only'        => $key->own_only,
			'access_level_id' => $key->access_level_id,
			'level_name'      => $this->levelName($key->access_level_id),
			'user_label'      => $this->userLabel($key->user_id),
		];
	}

	private function levelName(int $levelId): string {
		if($levelId < 1) {
			return '—';
		}
		$level = (new AccessLevelResolver())->findActive($levelId);
		if($level !== null) {
			return $level->name;
		}
		/** @var \DevCraft\Modules\DleApi\Repositories\ApiAccessLevelRepository $repo */
		$repo  = Application::instance()->database()->repository(\DevCraft\Modules\DleApi\Models\ApiAccessLevel::class);
		$found = $repo->find($levelId);

		return $found !== null ? $found->name : ('#' . $levelId);
	}

	private function userLabel(int $userId): string {
		if($userId < 1) {
			return __('гость');
		}
		foreach(Application::instance()->dleData()->users() as $row) {
			if((int) ($row['user_id'] ?? 0) !== $userId) {
				continue;
			}
			$name = trim((string) ($row['name'] ?? ''));

			return $name !== '' ? $name : ('#' . $userId);
		}

		return '#' . $userId;
	}

}
