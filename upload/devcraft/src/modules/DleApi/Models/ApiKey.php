<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Models;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use DevCraft\Core\Abstracts\AbstractEntity;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRepository;

/**
 * API-ключ (`{prefix}_api_keys`).
 */
#[Entity(role: 'api_key', repository: ApiKeyRepository::class, table: 'api_keys')]
#[Index(columns: ['api'], unique: true, name: 'api_keys_key_uindex')]
class ApiKey extends AbstractEntity {

	#[Column(type: 'string')]
	public string $api = '';

	#[Column(type: 'boolean', default: false)]
	public bool $is_admin = false;

	#[Column(type: 'boolean', default: false)]
	public bool $active = false;

	#[Column(type: 'integer', default: 0)]
	public int $user_id = 0;

	#[Column(type: 'boolean', default: true)]
	public bool $own_only = true;

	#[Column(type: 'bigInteger', default: 0)]
	public int $access_level_id = 0;

	public function __construct() {
		$this->createdAt = new \DateTimeImmutable();
	}

	public function getColumnVal(string $name): mixed {
		return match ($name) {
			'id'               => $this->id(),
			'api'              => $this->api,
			'is_admin'         => $this->is_admin,
			'active'           => $this->active,
			'user_id'          => $this->user_id,
			'own_only'         => $this->own_only,
			'access_level_id'  => $this->access_level_id,
			default            => null,
		};
	}

}
