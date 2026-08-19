<?php

declare(strict_types=1);

namespace DleApi\Tests\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Базовый класс интеграционных тестов DLE API v2.
 *
 * Создаёт один Guzzle-клиент и кэширует Bearer-токен
 * на весь прогон (один запрос /oauth/token для всех тестов).
 */
abstract class ApiTestCase extends TestCase
{
	/** @var Client|null Общий HTTP-клиент */
	private static ?Client $http = null;

	/** @var string|null Кэшированный Bearer access_token */
	private static ?string $bearer = null;

	/** Guzzle-клиент с base_uri и отключёнными исключениями. */
	protected static function http(): Client
	{
		if (self::$http === null) {
			self::$http = new Client([
				'base_uri'        => rtrim((string) getenv('API_BASE_URL'), '/') . '/',
				'http_errors'     => false,
				'timeout'         => 10,
				'connect_timeout' => 5,
			]);
		}

		return self::$http;
	}

	/**
	 * Возвращает Bearer access_token, полученный через
	 * POST /oauth/token {credential_type: api_key}.
	 *
	 * Токен кэшируется статически — один запрос на весь прогон.
	 */
	protected static function bearer(): string
	{
		if (self::$bearer !== null) {
			return self::$bearer;
		}

		$resp = self::http()->post('oauth/token', [
			'json' => [
				'credential_type' => 'api_key',
				'api_key'         => getenv('API_KEY'),
			],
		]);

		$body = self::json($resp);

		self::assertArrayHasKey(
			'access_token',
			$body,
			'Не удалось получить Bearer: ' . json_encode($body, JSON_UNESCAPED_UNICODE),
		);

		self::$bearer = (string) $body['access_token'];

		return self::$bearer;
	}

	/** Возвращает заголовок Authorization: Bearer для аутентифицированных запросов. */
	protected static function authHeader(): array
	{
		return ['Authorization' => 'Bearer ' . self::bearer()];
	}

	/** Декодирует тело ответа в массив. */
	protected static function json(Response|\Psr\Http\Message\ResponseInterface $response): array
	{
		$body = (string) $response->getBody();

		return (array) json_decode($body, true, 512, JSON_THROW_ON_ERROR);
	}

	/** Сбрасывает кэш токена между тест-сьютами (используется в RevokeTest). */
	protected static function resetBearer(): void
	{
		self::$bearer = null;
	}
}
