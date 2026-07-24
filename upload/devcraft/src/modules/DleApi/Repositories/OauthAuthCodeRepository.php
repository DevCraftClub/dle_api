<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Repositories;

use DevCraft\Core\Abstracts\AbstractRepository;
use DevCraft\Modules\DleApi\Models\OauthAuthCode;

/**
 * Репозиторий OAuth authorization codes.
 */
final class OauthAuthCodeRepository extends AbstractRepository {

	public function findValidByCode(string $code): ?OauthAuthCode {
		if($code === '') {
			return null;
		}
		/** @var OauthAuthCode|null $entity */
		$entity = $this->select()
			->where('code', $code)
			->where('revoked', false)
			->fetchOne();

		if($entity === null) {
			return null;
		}
		if($entity->expires_at->getTimestamp() < time()) {
			return null;
		}

		return $entity;
	}

	public function createCode(
		string $code,
		string $clientId,
		int $userId,
		string $scopes,
		string $redirectUri,
		string $codeChallenge,
		string $codeChallengeMethod,
		\DateTimeImmutable $expiresAt,
	): OauthAuthCode {
		$entity                         = new OauthAuthCode();
		$entity->code                   = $code;
		$entity->client_id              = $clientId;
		$entity->user_id                = $userId;
		$entity->scopes                 = $scopes !== '' ? $scopes : null;
		$entity->redirect_uri           = $redirectUri !== '' ? $redirectUri : null;
		$entity->code_challenge         = $codeChallenge !== '' ? $codeChallenge : null;
		$entity->code_challenge_method  = $codeChallengeMethod;
		$entity->expires_at             = $expiresAt;
		$entity->revoked                = false;

		/** @var OauthAuthCode $saved */
		$saved = $this->saveEntity($entity);

		return $saved;
	}

	public function revokeEntity(OauthAuthCode $code): void {
		$code->revoked = true;
		$this->saveEntity($code);
	}

}
