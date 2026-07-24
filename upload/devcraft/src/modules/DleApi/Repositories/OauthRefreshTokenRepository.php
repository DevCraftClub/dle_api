<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Repositories;

use DevCraft\Core\Abstracts\AbstractRepository;
use DevCraft\Modules\DleApi\Models\OauthRefreshToken;

/**
 * Репозиторий OAuth refresh tokens.
 */
final class OauthRefreshTokenRepository extends AbstractRepository {

	public function findValidByTokenId(string $tokenId): ?OauthRefreshToken {
		if($tokenId === '') {
			return null;
		}
		/** @var OauthRefreshToken|null $entity */
		$entity = $this->select()
			->where('token_id', $tokenId)
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

	public function createToken(string $tokenId, string $accessTokenId, \DateTimeImmutable $expiresAt): OauthRefreshToken {
		$entity                  = new OauthRefreshToken();
		$entity->token_id        = $tokenId;
		$entity->access_token_id = $accessTokenId;
		$entity->expires_at      = $expiresAt;
		$entity->revoked         = false;

		/** @var OauthRefreshToken $saved */
		$saved = $this->saveEntity($entity);

		return $saved;
	}

	public function revokeEntity(OauthRefreshToken $token): void {
		$token->revoked = true;
		$this->saveEntity($token);
	}

	public function revokeByTokenId(string $tokenId): void {
		/** @var list<OauthRefreshToken> $rows */
		$rows = $this->select()->where('token_id', $tokenId)->fetchAll();
		foreach($rows as $row) {
			$this->revokeEntity($row);
		}
	}

}
