<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Controller;

use DevCraft\Core\Application;
use DevCraft\Modules\DleApi\Models\ApiAccessLevel;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelRepository;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Собирает payload identity для `/me`, `/oauth/userinfo` и `/key/check`.
 */
final class MePayloadBuilder {

	/**
	 * @return array<string, mixed>
	 */
	public function fromRequest(Request $request): array {
		$token  = (array) $request->getAttribute('oauth_token');
		$key    = (array) $request->getAttribute('api_key');
		$userId = (int) ($token['user_id'] ?? $key['user_id'] ?? 0);

		return $this->build(
			$userId,
			$key,
			$token,
			$request->getAttribute('auth_via'),
		);
	}

	/**
	 * @param array<string, mixed> $key
	 * @param array<string, mixed> $token
	 * @return array<string, mixed>
	 */
	public function build(int $userId, array $key = [], array $token = [], mixed $authVia = NULL): array {
		$user = null;
		if($userId > 0) {
			$user = dle_api_find('users', $userId);
			if(is_array($user)) {
				unset($user['password'], $user['hash'], $user['allowed_ip']);
			}
		}

		return [
			'sub'            => $userId > 0 ? (string) $userId : null,
			'user_id'        => $userId,
			'name'           => is_array($user) ? ($user['name'] ?? null) : null,
			'email'          => is_array($user) ? ($user['email'] ?? null) : null,
			'user_group'     => is_array($user) ? (int) ($user['user_group'] ?? 0) : null,
			'user'           => $user,
			'api_key_id'     => (int) ($key['id'] ?? 0),
			'access_level'   => $this->accessLevelPayload((int) ($key['access_level_id'] ?? 0)),
			'client_id'      => $token['client_id'] ?? null,
			'auth_via'       => $authVia,
		];
	}

	/**
	 * @return array<string, mixed>|null
	 */
	public function accessLevelPayload(int $levelId): ?array {
		if($levelId < 1 || !class_exists(ApiAccessLevel::class)) {
			return null;
		}

		try {
			/** @var ApiAccessLevelRepository $repo */
			$repo   = Application::instance()->database()->repository(ApiAccessLevel::class);
			$entity = $repo->find($levelId);
			if($entity === null) {
				return null;
			}

			return [
				'id'          => $entity->id(),
				'name'        => $entity->name,
				'cheater'     => $entity->cheater,
				'own_only'    => $entity->own_only,
				'premoderate' => $entity->premoderate,
			];
		} catch(\Throwable) {
			return null;
		}
	}

}
