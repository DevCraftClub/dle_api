<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Models;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use DevCraft\Core\Abstracts\AbstractEntity;
use DevCraft\Modules\DleApi\Repositories\OauthRefreshTokenRepository;

/**
 * OAuth2 refresh token (`{prefix}_api_oauth_refresh_tokens`).
 */
#[Entity(role: 'api_oauth_refresh_token', repository: OauthRefreshTokenRepository::class, table: 'api_oauth_refresh_tokens')]
#[Index(columns: ['token_id'], unique: true, name: 'api_oauth_refresh_tokens_uindex')]
class OauthRefreshToken extends AbstractEntity {

	#[Column(type: 'string(100)')]
	public string $token_id = '';

	#[Column(type: 'string(100)')]
	public string $access_token_id = '';

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
			'id'              => $this->id(),
			'token_id'        => $this->token_id,
			'access_token_id' => $this->access_token_id,
			'expires_at'      => $this->expires_at,
			'revoked'         => $this->revoked,
			default           => null,
		};
	}

}
