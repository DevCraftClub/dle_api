<?php

declare(strict_types=1);

namespace DleApi\Tests\Integration;

/**
 * GET /health — публичный эндпоинт, не требует авторизации.
 */
final class HealthTest extends ApiTestCase
{
	public function testReturns200(): void
	{
		$resp = self::http()->get('health');

		$this->assertSame(200, $resp->getStatusCode());
	}

	public function testBodyContainsVersionAndApi(): void
	{
		$resp = self::http()->get('health');
		$body = self::json($resp);

		$this->assertArrayHasKey('version', $body);
		$this->assertArrayHasKey('api', $body);
		$this->assertSame('v2', $body['api']);
		$this->assertMatchesRegularExpression('/^\d+\.\d+\.\d+$/', (string) $body['version']);
	}

	public function testBodyContainsAuthField(): void
	{
		$body = self::json(self::http()->get('health'));

		$this->assertArrayHasKey('auth', $body);
		$this->assertSame('Bearer', $body['auth']);
	}
}
