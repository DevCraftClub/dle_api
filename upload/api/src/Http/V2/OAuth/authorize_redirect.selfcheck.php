<?php

declare(strict_types=1);

/**
 * ponytail: без БД — проверка логики совпадения redirect_uri и grant CSV.
 * Запуск: php upload/api/src/Http/V2/OAuth/authorize_redirect.selfcheck.php
 */

$match = static function (string $registered, string $request): bool {
	return $registered !== '' && $registered === $request;
};

assert($match('https://app.example/cb', 'https://app.example/cb') === true);
assert($match('https://app.example/cb', 'https://evil.example/cb') === false);
assert($match('', 'https://app.example/cb') === false);

$allows = static function (string $csv, string $grant): bool {
	if($csv === '') {
		return false;
	}

	return in_array($grant, array_map('trim', explode(',', $csv)), true);
};

assert($allows('authorization_code,refresh_token', 'authorization_code') === true);
assert($allows('client_credentials', 'authorization_code') === false);
assert($allows('', 'password') === false);

fwrite(STDOUT, "authorize_redirect.selfcheck: ok\n");
