<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Models;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use DevCraft\Core\Abstracts\AbstractEntity;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRequestRepository;

/**
 * Заявка на API-ключ (`{prefix}_api_key_requests`).
 */
#[Entity(role: 'api_key_request', repository: ApiKeyRequestRepository::class, table: 'api_key_requests')]
class ApiKeyRequest extends AbstractEntity {

	#[Column(type: 'integer', default: 0)]
	public int $user_id = 0;

	#[Column(type: 'bigInteger', default: 0)]
	public int $access_level_id = 0;

	/** pending|approved|denied */
	#[Column(type: 'string', default: 'pending')]
	public string $status = 'pending';

	#[Column(type: 'integer', default: 0)]
	public int $decided_by = 0;

	#[Column(type: 'datetime', nullable: true)]
	public ?\DateTimeImmutable $decided_at = null;

	#[Column(type: 'text', nullable: true)]
	public ?string $message = null;

	public function __construct() {
		$this->createdAt = new \DateTimeImmutable();
	}

	public function getColumnVal(string $name): mixed {
		return match ($name) {
			'id'               => $this->id(),
			'user_id'          => $this->user_id,
			'access_level_id'  => $this->access_level_id,
			'status'           => $this->status,
			'decided_by'       => $this->decided_by,
			'decided_at'       => $this->decided_at,
			'message'          => $this->message,
			default            => null,
		};
	}

}
