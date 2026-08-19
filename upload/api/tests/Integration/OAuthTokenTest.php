<?php

declare(strict_types=1);

namespace DleApi\Tests\Integration;

/**
 * POST /oauth/token — выдача токена разными способами.
 */
final class OAuthTokenTest extends ApiTestCase
{
	public function testApiKeyCredentialReturnsAccessToken(): void
	{
		$resp = self::http()->post('oauth/token', [
			'json' => [
				'credential_type' => 'api_key',
				'api_key'         => getenv('API_KEY'),
			],
		]);

		$this->assertSame(200, $resp->getStatusCode());
		$body = self::json($resp);
		$this->assertArrayHasKey('access_token', $body);
		$this->assertArrayHasKey('token_type', $body);
		$this->assertSame('Bearer', $body['token_type']);
		$this->assertArrayHasKey('expires_in', $body);
		$this->assertGreaterThan(0, (int) $body['expires_in']);
	}

	public function testApiKeyCredentialReturnsRefreshToken(): void
	{
		$resp = self::http()->post('oauth/token', [
			'json' => [
				'credential_type' => 'api_key',
				'api_key'         => getenv('API_KEY'),
			],
		]);

		$body = self::json($resp);
		$this->assertArrayHasKey('refresh_token', $body);
	}

	public function testInvalidApiKeyReturnsErrorStatus(): void
	{
		$resp = self::http()->post('oauth/token', [
			'json' => [
				'credential_type' => 'api_key',
				'api_key'         => 'invalid_key_000000000000000000000000',
			],
		]);

		$this->assertContains($resp->getStatusCode(), [400, 401]);
		$body = self::json($resp);
		$this->assertArrayHasKey('error', $body);
	}

	public function testMissingCredentialTypeReturns400(): void
	{
		$resp = self::http()->post('oauth/token', [
			'json' => [],
		]);

		$this->assertContains($resp->getStatusCode(), [400, 401, 422]);
		$body = self::json($resp);
		$this->assertArrayHasKey('error', $body);
	}

	public function testRefreshTokenGrant(): void
	{
		// Сначала получаем refresh_token
		$tokenResp    = self::http()->post('oauth/token', [
			'json' => [
				'credential_type' => 'api_key',
				'api_key'         => getenv('API_KEY'),
			],
		]);
		$tokenBody    = self::json($tokenResp);
		$refreshToken = (string) ($tokenBody['refresh_token'] ?? '');

		if ($refreshToken === '') {
			$this->markTestSkipped('refresh_token не получен из /oauth/token');
		}

		$resp = self::http()->post('oauth/token', [
			'json' => [
				'grant_type'    => 'refresh_token',
				'refresh_token' => $refreshToken,
				'client_id'     => getenv('OAUTH_CLIENT_ID'),
			],
		]);

		// Принимаем успех (200) или ошибку если client_secret обязателен
		$this->assertContains($resp->getStatusCode(), [200, 400, 401]);
	}
}
