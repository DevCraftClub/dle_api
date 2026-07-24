<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Repositories;

use DevCraft\Core\Abstracts\AbstractRepository;
use DevCraft\Modules\DleApi\Models\ApiAccessLevelGroup;

/**
 * Map группа DLE → уровень доступа.
 */
final class ApiAccessLevelGroupRepository extends AbstractRepository {

	/**
	 * @return list<ApiAccessLevelGroup>
	 */
	public function all(): array {
		/** @var list<ApiAccessLevelGroup> $rows */
		$rows = $this->select()->fetchAll();

		return $rows;
	}

	public function findByUserGroup(int $userGroupId): ?ApiAccessLevelGroup {
		/** @var ApiAccessLevelGroup|null $row */
		$row = $this->select()->where('user_group_id', $userGroupId)->fetchOne();

		return $row;
	}

	public function upsert(int $userGroupId, int $accessLevelId): ApiAccessLevelGroup {
		$row = $this->findByUserGroup($userGroupId);
		if($row === null) {
			$row                 = new ApiAccessLevelGroup();
			$row->user_group_id  = $userGroupId;
		}
		$row->access_level_id = $accessLevelId;
		/** @var ApiAccessLevelGroup $saved */
		$saved = $this->saveEntity($row);

		return $saved;
	}

	public function clear(int $userGroupId): void {
		$row = $this->findByUserGroup($userGroupId);
		if($row !== null) {
			$this->deleteEntity($row);
		}
	}

}
