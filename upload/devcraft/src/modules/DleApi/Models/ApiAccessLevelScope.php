<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Models;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use DevCraft\Core\Abstracts\AbstractEntity;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelScopeRepository;

/**
 * Scopes уровня доступа (`{prefix}_api_access_level_scopes`).
 */
#[Entity(role: 'api_access_level_scope', repository: ApiAccessLevelScopeRepository::class, table: 'api_access_level_scopes')]
class ApiAccessLevelScope extends AbstractEntity {

	#[Column(type: 'string', name: 'table', nullable: true)]
	public ?string $scope_table = null;

	#[Column(type: 'boolean', name: 'read', default: false)]
	public bool $can_read = false;

	#[Column(type: 'boolean', name: 'write', default: false)]
	public bool $can_write = false;

	#[Column(type: 'boolean', name: 'edit', default: false)]
	public bool $can_edit = false;

	#[Column(type: 'boolean', name: 'delete', default: false)]
	public bool $can_delete = false;

	#[Column(type: 'bigInteger', default: 0)]
	public int $access_level_id = 0;

	#[BelongsTo(target: ApiAccessLevel::class, innerKey: 'access_level_id', fkAction: 'CASCADE')]
	public ?ApiAccessLevel $accessLevel = null;

	public function __construct() {
		$this->createdAt = new \DateTimeImmutable();
	}

	public function getColumnVal(string $name): mixed {
		return match ($name) {
			'id'               => $this->id(),
			'table'            => $this->scope_table,
			'read'             => $this->can_read,
			'write'            => $this->can_write,
			'edit'             => $this->can_edit,
			'delete'           => $this->can_delete,
			'access_level_id'  => $this->access_level_id,
			default            => null,
		};
	}

}
