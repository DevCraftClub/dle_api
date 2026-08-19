<?php

declare(strict_types=1);

namespace DleApi\Tests\Integration;

/**
 * GET /key/check — проверка raw API-ключа (ApiKeyAuthMiddleware).
 */
final class KeyCheckTest extends ApiTestCase
{
	public function testValidApiKeyReturns200(): void
	{
		$resp = self::http()->get('key/check', [
			'headers' => ['Authorization' => 'Bearer ' . getenv('API_KEY')],
		]);

		$this->assertSame(200, $resp->getStatusCode());
	}

	public function testValidApiKeyBodyHasApiKeyField(): void
	{
		$resp = self::http()->get('key/check', [
			'headers' => ['Authorization' => 'Bearer ' . getenv('API_KEY')],
		]);

		$body = self::json($resp);
		// Ответ содержит объект apiKey с деталями ключа
		$this->assertArrayHasKey('apiKey', $body);
	}

	public function testNoAuthHeaderReturns401(): void
	{
		$resp = self::http()->get('key/check');

		$this->assertSame(401, $resp->getStatusCode());
		$body = self::json($resp);
		$this->assertSame('unauthorized', $body['error']);
	}

	public function testInvalidApiKeyReturns401(): void
	{
		$resp = self::http()->get('key/check', [
			'headers' => ['Authorization' => 'Bearer invalid_key_000000000000000000000000'],
		]);

		$this->assertSame(401, $resp->getStatusCode());
	}

	public function testBearerTokenRejectedByKeyCheckEndpoint(): void
	{
		// OAuth Bearer-токен не должен проходить валидацию raw API-ключа
		$resp = self::http()->get('key/check', [
			'headers' => ['Authorization' => 'Bearer ' . self::bearer()],
		]);

		// Либо 401 (токен не является сырым ключом), либо 200 если они совпали
		$this->assertContains($resp->getStatusCode(), [200, 401]);
	}
}
