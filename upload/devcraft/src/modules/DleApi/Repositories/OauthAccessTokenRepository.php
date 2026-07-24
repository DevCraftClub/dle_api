<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Repositories;

use DevCraft\Core\Abstracts\AbstractRepository;
use DevCraft\Modules\DleApi\Models\OauthAccessToken;

/**
 * Репозиторий OAuth access tokens.
 */
final class OauthAccessTokenRepository extends AbstractRepository {

	public function findValidByAccessToken(string $bearer): ?OauthAccessToken {
		if($bearer === '') {
			return null;
		}
		/** @var OauthAccessToken|null $entity */
		$entity = $this->select()
			->where('access_token', $bearer)
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

	public function findByTokenId(string $tokenId): ?OauthAccessToken {
		/** @var OauthAccessToken|null $entity */
		$entity = $this->select()->where('token_id', $tokenId)->fetchOne();

		return $entity;
	}

	public function createToken(
		string $tokenId,
		string $accessToken,
		string $clientId,
		int $userId,
		int $apiKeyId,
		string $scopes,
		\DateTimeImmutable $expiresAt,
	): OauthAccessToken {
		$entity               = new OauthAccessToken();
		$entity->token_id     = $tokenId;
		$entity->access_token = $accessToken;
		$entity->client_id    = $clientId;
		$entity->user_id      = $userId;
		$entity->api_key_id   = $apiKeyId;
		$entity->scopes       = $scopes !== '' ? $scopes : null;
		$entity->expires_at   = $expiresAt;
		$entity->revoked      = false;

		/** @var OauthAccessToken $saved */
		$saved = $this->saveEntity($entity);

		return $saved;
	}

	public function revokeEntity(OauthAccessToken $token): void {
		$token->revoked = true;
		$this->saveEntity($token);
	}

	public function revokeByAccessOrTokenId(string $token): void {
		foreach([$this->select()->where('access_token', $token)->fetchAll(), $this->select()->where('token_id', $token)->fetchAll()] as $list) {
			/** @var list<OauthAccessToken> $list */
			foreach($list as $row) {
				$this->revokeEntity($row);
			}
		}
	}

}
