<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Controller;

use DleApi\Http\V2\JsonResponder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * GET /me и OAuth userinfo — субъект AuthToken.
 */
final class MeController {

	public function __construct(
		private readonly MePayloadBuilder $payloadBuilder = new MePayloadBuilder(),
	) {}

	public function me(Request $request, Response $_response): Response {
		return JsonResponder::ok($this->payloadBuilder->fromRequest($request));
	}

}
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Controller;

use DleApi\Http\V2\JsonResponder;
use DevCraft\Modules\DleApi\Models\ApiAccessLevel;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelRepository;
use DevCraft\Core\Application;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * GET /me и OAuth userinfo — субъект AuthToken.
 */
final class MeController {

	public function me(Request $request, Response $_response): Response {
		$token = (array) $request->getAttribute('oauth_token');
		$key   = (array) $request->getAttribute('api_key');
		$userId = (int) ($token['user_id'] ?? $key['user_id'] ?? 0);

		$user = null;
		if($userId > 0) {
			$user = dle_api_find('users', $userId);
			if(is_array($user)) {
				unset($user['password'], $user['hash'], $user['allowed_ip']);
			}
		}

		$level = null;
		$levelId = (int) ($key['access_level_id'] ?? 0);
		if($levelId > 0 && class_exists(ApiAccessLevel::class)) {
			try {
				/** @var ApiAccessLevelRepository $repo */
				$repo  = Application::instance()->database()->repository(ApiAccessLevel::class);
				$entity = $repo->find($levelId);
				if($entity !== null) {
					$level = [
						'id'          => $entity->id(),
						'name'        => $entity->name,
						'cheater'     => $entity->cheater,
						'own_only'    => $entity->own_only,
						'premoderate' => $entity->premoderate,
					];
				}
			} catch(\Throwable) {
				$level = null;
			}
		}

		return JsonResponder::ok([
			'sub'            => $userId > 0 ? (string) $userId : null,
			'user_id'        => $userId,
			'name'           => is_array($user) ? ($user['name'] ?? null) : null,
			'email'          => is_array($user) ? ($user['email'] ?? null) : null,
			'user_group'     => is_array($user) ? (int) ($user['user_group'] ?? 0) : null,
			'user'           => $user,
			'api_key_id'     => (int) ($key['id'] ?? 0),
			'access_level'   => $level,
			'client_id'      => $token['client_id'] ?? null,
			'auth_via'       => $request->getAttribute('auth_via'),
		]);
	}

}
>>>>>>> Current commit: Начало обновления до api v2
