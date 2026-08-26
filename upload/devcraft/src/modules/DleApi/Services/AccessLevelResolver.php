<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Services;

use DevCraft\Core\Application;
use DevCraft\Modules\DleApi\Models\ApiAccessLevel;
use DevCraft\Modules\DleApi\Models\ApiAccessLevelGroup;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelGroupRepository;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelRepository;
use DevCraft\Core\Support\DleDataService;

/**
 * Резолв уровня доступа: группа DLE → map → default из настроек.
 */
final class AccessLevelResolver {

	public function forUserGroup(int $userGroupId): ?ApiAccessLevel {
		/** @var ApiAccessLevelGroupRepository $mapRepo */
		$mapRepo = Application::instance()->database()->repository(ApiAccessLevelGroup::class);
		$map     = $mapRepo->findByUserGroup($userGroupId);
		if($map !== null && $map->access_level_id > 0) {
			return $this->findActive($map->access_level_id);
		}

		return $this->defaultLevel();
	}

	public function forUserId(int $userId): ?ApiAccessLevel {
		if($userId < 1) {
			return $this->defaultLevel();
		}

		$row = DleDataService::user(id: $userId);

		return $this->forUserGroup((int) ($row['user_group'] ?? 0));
	}

	public function defaultLevel(): ?ApiAccessLevel {
		$cfg = DleApiConfig::all();
		$id  = (int) ($cfg['default_access_level_id'] ?? 0);

		return $id > 0 ? $this->findActive($id) : null;
	}

	public function findActive(int $id): ?ApiAccessLevel {
		/** @var ApiAccessLevelRepository $repo */
		$repo  = Application::instance()->database()->repository(ApiAccessLevel::class);
		$level = $repo->find($id);
		if($level === null || !$level->active) {
			return null;
		}

		return $level;
	}

}
