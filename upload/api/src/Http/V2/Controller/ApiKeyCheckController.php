<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Controller;

use DleApi\Http\V2\JsonResponder;
use DevCraft\Modules\DleApi\Models\ApiAccessLevelScope;
use DevCraft\Modules\DleApi\Models\ApiKey;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelScopeRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Проверка сырого API-ключа.
 */
final class ApiKeyCheckController {

	public function __construct(
		private readonly MePayloadBuilder $mePayloadBuilder = new MePayloadBuilder(),
	) {}

	public function check(Request $request, Response $_response): Response {
		/** @var ApiKey|null $keyEntity */
		$keyEntity = $request->getAttribute('api_key_entity');
		$key       = (array) $request->getAttribute('api_key');
		$levelId   = (int) ($key['access_level_id'] ?? 0);

		return JsonResponder::ok([
			'apiKey' => [
				'key'         => (string) ($key['api'] ?? ''),
				'validFrom'   => $keyEntity?->createdAt()?->format(DATE_ATOM),
				'validTo'     => null,
				'accessLevel' => $this->mePayloadBuilder->accessLevelPayload($levelId),
			],
			'scopes' => $this->buildScopes($levelId),
			'me'     => $this->mePayloadBuilder->fromRequest($request),
		]);
	}

	/**
	 * @return array<string, array{read: bool, write: bool, edit: bool, delete: bool}>
	 */
	private function buildScopes(int $levelId): array {
		if($levelId < 1) {
			return [];
		}

		/** @var ApiAccessLevelScopeRepository $repo */
		$repo = \DevCraft\Core\Application::instance()->database()->repository(ApiAccessLevelScope::class);

		$map = [];
		foreach($repo->forLevel($levelId) as $scope) {
			$table = (string) ($scope->scope_table ?? '');
			if($table === '') {
				continue;
			}
			$map[$table] = [
				'read'   => $scope->can_read,
				'write'  => $scope->can_write,
				'edit'   => $scope->can_edit,
				'delete' => $scope->can_delete,
			];
		}

		return $map;
	}

}
