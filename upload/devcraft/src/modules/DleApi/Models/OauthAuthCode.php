<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Models;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use DevCraft\Core\Abstracts\AbstractEntity;
use DevCraft\Modules\DleApi\Repositories\OauthAuthCodeRepository;

/**
 * OAuth2 authorization code (`{prefix}_api_oauth_auth_codes`).
 */
#[Entity(role: 'api_oauth_auth_code', repository: OauthAuthCodeRepository::class, table: 'api_oauth_auth_codes')]
#[Index(columns: ['code'], unique: true, name: 'api_oauth_auth_codes_uindex')]
class OauthAuthCode extends AbstractEntity {

	#[Column(type: 'string')]
	public string $code = '';

	#[Column(type: 'string(80)')]
	public string $client_id = '';

	#[Column(type: 'integer', default: 0)]
	public int $user_id = 0;

	#[Column(type: 'text', nullable: true)]
	public ?string $scopes = null;

	#[Column(type: 'text', nullable: true)]
	public ?string $redirect_uri = null;

	#[Column(type: 'string', nullable: true)]
	public ?string $code_challenge = null;

	#[Column(type: 'string(10)', nullable: true)]
	public ?string $code_challenge_method = null;

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
			'id'                     => $this->id(),
			'code'                   => $this->code,
			'client_id'              => $this->client_id,
			'user_id'                => $this->user_id,
			'scopes'                 => $this->scopes,
			'redirect_uri'           => $this->redirect_uri,
			'code_challenge'         => $this->code_challenge,
			'code_challenge_method'  => $this->code_challenge_method,
			'expires_at'             => $this->expires_at,
			'revoked'                => $this->revoked,
			default                  => null,
		};
	}

}
