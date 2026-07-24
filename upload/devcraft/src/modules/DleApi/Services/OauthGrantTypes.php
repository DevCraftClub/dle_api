<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Services;

/**
 * Нормализация grant_types OAuth-клиента.
 */
final class OauthGrantTypes {

	public const ALLOWED = [
		'authorization_code',
		'refresh_token',
		'client_credentials',
		'password',
	];

	public const DEFAULT = 'authorization_code,refresh_token,client_credentials,password';

	/**
	 * @param mixed $raw CSV-строка или список значений из формы
	 */
	public static function normalize(mixed $raw): string {
		if(is_string($raw)) {
			$parts = array_map('trim', explode(',', $raw));
		} elseif(is_array($raw)) {
			$parts = [];
			foreach($raw as $item) {
				if(is_scalar($item) || $item === null) {
					$parts[] = trim((string) $item);
				}
			}
		} else {
			$parts = [];
		}

		$selected = array_fill_keys(array_intersect(self::ALLOWED, $parts), true);
		$ordered  = [];
		foreach(self::ALLOWED as $grant) {
			if(isset($selected[$grant])) {
				$ordered[] = $grant;
			}
		}

		return implode(',', $ordered);
	}

}
