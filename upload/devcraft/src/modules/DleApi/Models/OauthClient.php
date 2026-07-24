<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Models;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Table\Index;
use DevCraft\Core\Abstracts\AbstractEntity;
use DevCraft\Modules\DleApi\Repositories\OauthClientRepository;

/**
 * OAuth2-клиент (`{prefix}_api_oauth_clients`).
 */
#[Entity(role: 'api_oauth_client', repository: OauthClientRepository::class, table: 'api_oauth_clients')]
#[Index(columns: ['client_id'], unique: true, name: 'api_oauth_clients_cid_uindex')]
class OauthClient extends AbstractEntity {

	#[Column(type: 'string(80)')]
	public string $client_id = '';

	#[Column(type: 'string')]
	public string $client_secret = '';

	#[Column(type: 'string', default: '')]
	public string $name = '';

	#[Column(type: 'text', nullable: true)]
	public ?string $redirect_uri = null;

	#[Column(type: 'string', default: 'authorization_code,refresh_token,client_credentials,password')]
	public string $grant_types = 'authorization_code,refresh_token,client_credentials,password';

	#[Column(type: 'bigInteger')]
	public int $api_key_id = 0;

	#[Column(type: 'boolean', default: true)]
	public bool $active = true;

	#[BelongsTo(target: ApiKey::class, innerKey: 'api_key_id', fkAction: 'CASCADE')]
	public ?ApiKey $apiKey = null;

	public function __construct() {
		$this->createdAt = new \DateTimeImmutable();
	}

	/**
	 * Превью связанного API-ключа для списка в админке.
	 */
	public function apiKeyPreview(): string {
		return $this->apiKey?->api ?? '';
	}

	public function getColumnVal(string $name): mixed {
		return match ($name) {
			'id'            => $this->id(),
			'client_id'     => $this->client_id,
			'client_secret' => $this->client_secret,
			'name'          => $this->name,
			'redirect_uri'  => $this->redirect_uri,
			'grant_types'   => $this->grant_types,
			'api_key_id'    => $this->api_key_id,
			'active'        => $this->active,
			default         => null,
		};
	}

}
