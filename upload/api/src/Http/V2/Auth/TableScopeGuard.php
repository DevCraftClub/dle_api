<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Auth;

use DevCraft\Core\Application;
use DevCraft\Modules\DleApi\Models\ApiAccessLevel;
use DevCraft\Modules\DleApi\Models\ApiAccessLevelScope;
use DevCraft\Modules\DleApi\Models\ApiScope;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelRepository;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelScopeRepository;
use DevCraft\Modules\DleApi\Repositories\ApiScopeRepository;
use DleApi\Sdk\SdkException;
use JetBrains\PhpStorm\ExpectedValues;

/**
 * Проверка scopes / cheater для HTTP /table/*.
 */
final class TableScopeGuard {

	/**
	 * @param array<string, mixed> $apiKey
	 */
	public function assert(
		array $apiKey,
		string $table,
		#[ExpectedValues(values: ['read', 'write', 'edit', 'delete'])]
		string $action,
	): void {
		$levelId = (int) ($apiKey['access_level_id'] ?? 0);
		if($levelId > 0) {
			/** @var ApiAccessLevelRepository $levels */
			$levels = Application::instance()->database()->repository(ApiAccessLevel::class);
			$level  = $levels->find($levelId);
			if($level !== null && $level->cheater) {
				return;
			}
			if($level !== null) {
				/** @var ApiAccessLevelScopeRepository $scopes */
				$scopes = Application::instance()->database()->repository(ApiAccessLevelScope::class);
				if(!$scopes->allows($levelId, $table, $action)) {
					throw SdkException::forbiddenScope($table, $action);
				}

				return;
			}
		}

		if(!empty($apiKey['is_admin'])) {
			return;
		}
		$keyId = (int) ($apiKey['id'] ?? 0);
		if($keyId < 1) {
			throw SdkException::forbiddenScope($table, $action);
		}
		/** @var ApiScopeRepository $repo */
		$repo = Application::instance()->database()->repository(ApiScope::class);
		if(!$repo->allows($keyId, $table, $action)) {
			throw SdkException::forbiddenScope($table, $action);
		}
	}

	/**
	 * @param array<string, mixed> $apiKey
	 */
	public function isOwnOnly(array $apiKey): bool {
		$levelId = (int) ($apiKey['access_level_id'] ?? 0);
		if($levelId > 0) {
			/** @var ApiAccessLevelRepository $levels */
			$levels = Application::instance()->database()->repository(ApiAccessLevel::class);
			$level  = $levels->find($levelId);
			if($level !== null) {
				return $level->own_only && !$level->cheater;
			}
		}

		return !empty($apiKey['own_only']) && empty($apiKey['is_admin']);
	}

}
