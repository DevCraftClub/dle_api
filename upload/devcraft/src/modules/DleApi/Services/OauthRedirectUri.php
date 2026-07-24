<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Services;

/**
 * Проверка redirect_uri OAuth-клиента.
 */
final class OauthRedirectUri {

	/**
	 * Пустая строка → null (URI не задан). Невалидная ссылка → false.
	 */
	public static function normalize(string $uri): string|false|null {
		$uri = trim($uri);
		if($uri === '') {
			return null;
		}

		if(filter_var($uri, FILTER_VALIDATE_URL) === false) {
			return false;
		}

		$scheme = strtolower((string) (parse_url($uri, PHP_URL_SCHEME) ?? ''));
		if(!in_array($scheme, ['http', 'https'], true)) {
			return false;
		}

		$host = (string) (parse_url($uri, PHP_URL_HOST) ?? '');
		if($host === '') {
			return false;
		}

		return $uri;
	}

}
