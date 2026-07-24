<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Models;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use DevCraft\Core\Abstracts\AbstractEntity;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelGroupRepository;

/**
 * Связь группа DLE ↔ уровень доступа (`{prefix}_api_access_level_groups`).
 */
#[Entity(role: 'api_access_level_group', repository: ApiAccessLevelGroupRepository::class, table: 'api_access_level_groups')]
#[Index(columns: ['user_group_id'], unique: true, name: 'api_access_level_groups_ug_uindex')]
class ApiAccessLevelGroup extends AbstractEntity {

	#[Column(type: 'integer', default: 0)]
	public int $user_group_id = 0;

	#[Column(type: 'bigInteger', default: 0)]
	public int $access_level_id = 0;

	public function __construct() {
		$this->createdAt = new \DateTimeImmutable();
	}

	public function getColumnVal(string $name): mixed {
		return match ($name) {
			'id'               => $this->id(),
			'user_group_id'    => $this->user_group_id,
			'access_level_id'  => $this->access_level_id,
			default            => null,
		};
	}

}
