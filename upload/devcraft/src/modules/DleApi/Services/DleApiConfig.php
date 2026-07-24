<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Services;

use DevCraft\Core\Support\DataManager;

/**
 * Конфиг dleapi.json + overlay из ROOT/.env (DEMO_MODE, DLEAPI_SECURE).
 */
final class DleApiConfig {

	/** @var array<string, mixed>|null */
	private static ?array $cache = null;

	/**
	 * @return array<string, mixed>
	 */
	public static function all(): array {
		if(self::$cache !== null) {
			return self::$cache;
		}

		$cfg = DataManager::getConfig('dleapi', null, 'dleapi');
		if(!is_array($cfg)) {
			$cfg = [];
		}

		$env = self::readEnvFile();
		if(self::envBool($env, 'DEMO_MODE')) {
			$cfg['demo_mode'] = true;
			$cfg['secure']    = true;
		} else {
			$cfg['demo_mode'] = false;
		}
		if(self::envBool($env, 'DLEAPI_SECURE')) {
			$cfg['secure'] = true;
		}
		if(array_key_exists('DLEAPI_SECURE', $env) && !self::envBool($env, 'DLEAPI_SECURE') && empty($cfg['demo_mode'])) {
			$cfg['secure'] = false;
		}

		$cfg['notify_group_ids'] = self::parseIdList($cfg['notify_group_ids'] ?? '1');
		$cfg['notify_user_ids']  = self::parseIdList($cfg['notify_user_ids'] ?? '');

		self::$cache = $cfg;

		return self::$cache;
	}

	/**
	 * @param mixed $raw
	 * @return list<int>
	 */
	private static function parseIdList(mixed $raw): array {
		if(is_array($raw)) {
			return array_values(array_unique(array_filter(array_map('intval', $raw))));
		}
		$parts = preg_split('/[\s,;]+/', (string) $raw) ?: [];

		return array_values(array_unique(array_filter(array_map('intval', $parts))));
	}

	public static function isDemoMode(): bool {
		return !empty(self::all()['demo_mode']);
	}

	public static function isSecure(): bool {
		return !empty(self::all()['secure']) || self::isDemoMode();
	}

	public static function resetCache(): void {
		self::$cache = null;
	}

	/**
	 * @return array{demo_mode: true, authorized: true, access_token: null, message: string}
	 */
	public static function demoAuthorizedResponse(): array {
		return [
			'demo_mode'    => true,
			'authorized'   => true,
			'access_token' => null,
			'message'      => __('Авторизация пройдена, но сброшена в демо-режиме'),
		];
	}

	/**
	 * @return array<string, string>
	 */
	private static function readEnvFile(): array {
		$path = (defined('ROOT_DIR') ? ROOT_DIR : dirname(__DIR__, 5)) . '/.env';
		if(!is_file($path)) {
			return [];
		}
		$out = [];
		foreach(file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
			$line = trim($line);
			if($line === '' || str_starts_with($line, '#')) {
				continue;
			}
			if(!str_contains($line, '=')) {
				continue;
			}
			[$k, $v] = explode('=', $line, 2);
			$out[trim($k)] = trim($v, " \t\"'");
		}

		return $out;
	}

	/**
	 * @param array<string, string> $env
	 */
	private static function envBool(array $env, string $key): bool {
		if(!array_key_exists($key, $env)) {
			return false;
		}
		$v = strtolower($env[$key]);

		return in_array($v, ['1', 'true', 'yes', 'on'], true);
	}

}
