<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Models;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use DevCraft\Core\Abstracts\AbstractEntity;
use DevCraft\Modules\DleApi\Repositories\OauthAccessTokenRepository;

/**
 * OAuth2 access token (`{prefix}_api_oauth_access_tokens`).
 */
#[Entity(role: 'api_oauth_access_token', repository: OauthAccessTokenRepository::class, table: 'api_oauth_access_tokens')]
#[Index(columns: ['token_id'], unique: true, name: 'api_oauth_access_tokens_uindex')]
class OauthAccessToken extends AbstractEntity {

	#[Column(type: 'string(100)')]
	public string $token_id = '';

	#[Column(type: 'text')]
	public string $access_token = '';

	#[Column(type: 'string(80)')]
	public string $client_id = '';

	#[Column(type: 'integer', default: 0)]
	public int $user_id = 0;

	#[Column(type: 'bigInteger', default: 0)]
	public int $api_key_id = 0;

	#[Column(type: 'text', nullable: true)]
	public ?string $scopes = null;

	#[Column(type: 'datetime')]
	public \DateTimeImmutable $expires_at;

	#[Column(type: 'boolean', default: false)]
	public bool $revoked = false;

	public function __construct() {
		$this->createdAt  = new \DateTimeImmutable();
		$this->expires_at = new \DateTimeImmutable();
	}

	public function getColumnVal(string $name): mixed {
		return match ($name) {
			'id'           => $this->id(),
			'token_id'     => $this->token_id,
			'access_token' => $this->access_token,
			'client_id'    => $this->client_id,
			'user_id'      => $this->user_id,
			'api_key_id'   => $this->api_key_id,
			'scopes'       => $this->scopes,
			'expires_at'   => $this->expires_at,
			'revoked'      => $this->revoked,
			default        => null,
		};
	}

}
