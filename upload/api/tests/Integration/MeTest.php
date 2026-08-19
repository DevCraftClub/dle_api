<?php

declare(strict_types=1);

namespace DleApi\Tests\Integration;

/**
 * GET /me и GET /oauth/userinfo — информация о владельце Bearer-токена.
 */
final class MeTest extends ApiTestCase
{
	public function testMeWithBearerReturns200(): void
	{
		$resp = self::http()->get('me', [
			'headers' => self::authHeader(),
		]);

		$this->assertSame(200, $resp->getStatusCode());
	}

	public function testMeBodyHasUserIdAndName(): void
	{
		$resp = self::http()->get('me', [
			'headers' => self::authHeader(),
		]);

		$body = self::json($resp);
		$this->assertArrayHasKey('user_id', $body);
		$this->assertArrayHasKey('name', $body);
		$this->assertGreaterThan(0, (int) $body['user_id']);
	}

	public function testMeWithoutBearerReturns401(): void
	{
		$resp = self::http()->get('me');

		$this->assertSame(401, $resp->getStatusCode());
		$body = self::json($resp);
		$this->assertArrayHasKey('error', $body);
		$this->assertSame('unauthorized', $body['error']);
	}

	public function testUserinfoWithBearerReturns200(): void
	{
		$resp = self::http()->get('oauth/userinfo', [
			'headers' => self::authHeader(),
		]);

		$this->assertSame(200, $resp->getStatusCode());
	}

	public function testUserinfoBodyHasSubField(): void
	{
		$resp = self::http()->get('oauth/userinfo', [
			'headers' => self::authHeader(),
		]);

		$body = self::json($resp);
		// OIDC userinfo обязан вернуть sub (user id)
		$this->assertArrayHasKey('sub', $body);
	}

	public function testUserinfoWithoutBearerReturns401(): void
	{
		$resp = self::http()->get('oauth/userinfo');

		$this->assertSame(401, $resp->getStatusCode());
	}
}
