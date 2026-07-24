<?php

declare(strict_types=1);

namespace DleApi\Http\V2\OAuth;

/**
 * Проверка логина/пароля пользователя DLE (как engine/api external_auth).
 *
 * Поле username: name или email.
 */
final class DleUserPasswordVerifier {

	/**
	 * @return int|null user_id при успехе; null при ошибке / неоднозначности
	 */
	public function verify(string $username, string $password): ?int {
		$username = trim($username);

		if($username === '' || $password === '') {
			return null;
		}

		$prefix = defined('USERPREFIX') ? (string) USERPREFIX : (string) PREFIX;
		$table  = $prefix . '_users';
		$rows   = dle_api_db()->query(
			"SELECT user_id, password FROM {$table} WHERE name = ? OR email = ? LIMIT 3",
			[$username, $username],
		)->fetchAll();

		if(!is_array($rows) || $rows === [] || count($rows) !== 1) {
			return null;
		}

		$row = $rows[0];
		$hash = (string) ($row['password'] ?? '');
		$id   = (int) ($row['user_id'] ?? 0);

		if($id < 1 || $hash === '' || !$this->passwordMatches($password, $hash)) {
			return null;
		}

		return $id;
	}

	/**
	 * Сравнение пароля с хешем из users.password (md5-double или password_hash).
	 *
	 * @internal
	 */
	public function passwordMatches(string $plain, string $stored): bool {
		if(self::isMd5Hash($stored)) {
			return hash_equals($stored, md5(md5($plain)));
		}

		return password_verify($plain, $stored);
	}

	/**
	 * @internal
	 */
	public static function isMd5Hash(string $value): bool {
		return strlen($value) === 32 && ctype_xdigit($value);
	}

}
