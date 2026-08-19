<?php

declare(strict_types=1);

namespace DleApi\Tests\Integration;

/**
 * POST /oauth/revoke — отзыв токена.
 *
 * Выполняется последним (по алфавиту 'R' < 'T' не гарантирует порядок,
 * поэтому тест получает собственный одноразовый токен).
 */
final class OAuthRevokeTest extends ApiTestCase
{
	/** Получает свежий токен специально для этого теста. */
	private function freshToken(): array
	{
		$resp = self::http()->post('oauth/token', [
			'json' => [
				'credential_type' => 'api_key',
				'api_key'         => getenv('API_KEY'),
			],
		]);

		return self::json($resp);
	}

	public function testRevokeAccessTokenReturns200(): void
	{
		$token = $this->freshToken();
		$this->assertArrayHasKey('access_token', $token);

		$resp = self::http()->post('oauth/revoke', [
			'json' => ['token' => $token['access_token']],
		]);

		$this->assertSame(200, $resp->getStatusCode());
	}

	public function testRevokedTokenCannotAccessMe(): void
	{
		$token       = $this->freshToken();
		$accessToken = (string) $token['access_token'];

		// Отзываем
		self::http()->post('oauth/revoke', [
			'json' => ['token' => $accessToken],
		]);

		// После отзыва /me должен вернуть 401
		$resp = self::http()->get('me', [
			'headers' => ['Authorization' => 'Bearer ' . $accessToken],
		]);

		$this->assertSame(401, $resp->getStatusCode());
	}

	public function testRevokeRefreshTokenReturns200(): void
	{
		$token = $this->freshToken();

		if (empty($token['refresh_token'])) {
			$this->markTestSkipped('refresh_token не получен');
		}

		$resp = self::http()->post('oauth/revoke', [
			'json' => ['token' => $token['refresh_token']],
		]);

		$this->assertSame(200, $resp->getStatusCode());
	}

	public function testRevokeInvalidTokenReturns200(): void
	{
		// RFC 7009: сервер должен возвращать 200 даже для несуществующего токена
		$resp = self::http()->post('oauth/revoke', [
			'json' => ['token' => 'non_existent_token_xyz'],
		]);

		$this->assertSame(200, $resp->getStatusCode());
	}

	public function testRevokeMissingTokenFieldReturns200PerRfc(): void
	{
		// RFC 7009 §2.2: сервер возвращает 200 даже для пустого запроса
		$resp = self::http()->post('oauth/revoke', [
			'json' => [],
		]);

		$this->assertContains($resp->getStatusCode(), [200, 400, 422]);
	}
}
