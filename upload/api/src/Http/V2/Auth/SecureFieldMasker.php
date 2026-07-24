<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Auth;

use DevCraft\Core\Application;
use DevCraft\Modules\DleApi\Models\ApiAccessLevel;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelRepository;
use DevCraft\Modules\DleApi\Services\DleApiConfig;
use DleApi\Sdk\SdkException;

/**
 * Маскирование чувствительных полей в ответах CRUD.
 */
final class SecureFieldMasker {

	private const IP_FIELDS = ['ip', 'logged_ip', 'allowed_ip', 'lasthit_ip'];

	private const PASSWORD_FIELDS = ['password', 'hash', 'password_hash', 'pass', 'secret', 'client_secret'];

	private const PERSONAL_FIELDS = ['fullname', 'land', 'city', 'location', 'fullnamename', 'x_fullname'];

	/**
	 * @param array<string, mixed> $apiKey
	 * @param list<array<string, mixed>>|array<string, mixed> $data
	 * @return list<array<string, mixed>>|array<string, mixed>
	 */
	public function mask(array $apiKey, array $data): array {
		if(!$this->shouldMask($apiKey)) {
			return $data;
		}
		$flags = $this->flags($apiKey);
		if(array_is_list($data)) {
			return array_map(fn(array $row): array => $this->maskRow($row, $flags), $data);
		}

		return $this->maskRow($data, $flags);
	}

	/**
	 * @param array<string, mixed> $apiKey
	 */
	public function assertOwnOnlyRow(array $apiKey, ?array $row, string $table): void {
		$guard = new TableScopeGuard();
		if(!$guard->isOwnOnly($apiKey) || $row === null) {
			return;
		}
		$owner = (int) ($apiKey['user_id'] ?? 0);
		$tokenUser = $owner;
		$ok = false;
		if(isset($row['user_id']) && (int) $row['user_id'] === $tokenUser) {
			$ok = true;
		}
		if(isset($row['autor']) && $tokenUser > 0) {
			$user = dle_api_find('users', $tokenUser);
			if(is_array($user) && strcasecmp((string) ($user['name'] ?? ''), (string) $row['autor']) === 0) {
				$ok = true;
			}
		}
		if(isset($row['user']) && (int) $row['user'] === $tokenUser) {
			$ok = true;
		}
		if(!$ok && $tokenUser > 0) {
			throw SdkException::forbiddenScope($table, 'own_only');
		}
	}

	/**
	 * @param array<string, mixed> $apiKey
	 */
	private function shouldMask(array $apiKey): bool {
		if(DleApiConfig::isSecure()) {
			return true;
		}
		$levelId = (int) ($apiKey['access_level_id'] ?? 0);
		if($levelId < 1) {
			return false;
		}
		/** @var ApiAccessLevelRepository $repo */
		$repo  = Application::instance()->database()->repository(ApiAccessLevel::class);
		$level = $repo->find($levelId);

		return $level !== null && ($level->mask_ip || $level->mask_passwords || $level->mask_personal);
	}

	/**
	 * @param array<string, mixed> $apiKey
	 * @return array{ip: bool, passwords: bool, personal: bool}
	 */
	private function flags(array $apiKey): array {
		if(DleApiConfig::isDemoMode() || DleApiConfig::isSecure()) {
			return ['ip' => true, 'passwords' => true, 'personal' => true];
		}
		$levelId = (int) ($apiKey['access_level_id'] ?? 0);
		if($levelId > 0) {
			/** @var ApiAccessLevelRepository $repo */
			$repo  = Application::instance()->database()->repository(ApiAccessLevel::class);
			$level = $repo->find($levelId);
			if($level !== null) {
				return [
					'ip'        => $level->mask_ip,
					'passwords' => $level->mask_passwords,
					'personal'  => $level->mask_personal,
				];
			}
		}

		return ['ip' => false, 'passwords' => false, 'personal' => false];
	}

	/**
	 * @param array<string, mixed> $row
	 * @param array{ip: bool, passwords: bool, personal: bool} $flags
	 * @return array<string, mixed>
	 */
	private function maskRow(array $row, array $flags): array {
		foreach($row as $k => $v) {
			$lk = strtolower((string) $k);
			if($flags['passwords'] && in_array($lk, self::PASSWORD_FIELDS, true)) {
				$row[$k] = '***';
			}
			if($flags['ip'] && in_array($lk, self::IP_FIELDS, true)) {
				$row[$k] = '***';
			}
			if($flags['personal'] && in_array($lk, self::PERSONAL_FIELDS, true)) {
				$row[$k] = '***';
			}
		}

		return $row;
	}

}
