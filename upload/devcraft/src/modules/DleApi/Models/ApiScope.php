<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Models;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use DevCraft\Core\Abstracts\AbstractEntity;
use DevCraft\Modules\DleApi\Repositories\ApiScopeRepository;

/**
 * Права API-ключа на таблицу (`{prefix}_api_scope`).
 */
#[Entity(role: 'api_scope', repository: ApiScopeRepository::class, table: 'api_scope')]
class ApiScope extends AbstractEntity {

	/** Имя таблицы (колонка `table`). */
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
	public int $key_id = 0;

	#[BelongsTo(target: ApiKey::class, innerKey: 'key_id', fkAction: 'CASCADE')]
	public ?ApiKey $apiKey = null;

	public function __construct() {
		$this->createdAt = new \DateTimeImmutable();
	}

	public function getColumnVal(string $name): mixed {
		return match ($name) {
			'id'      => $this->id(),
			'table'   => $this->scope_table,
			'read'    => $this->can_read,
			'write'   => $this->can_write,
			'edit'    => $this->can_edit,
			'delete'  => $this->can_delete,
			'key_id'  => $this->key_id,
			default   => null,
		};
	}

}
