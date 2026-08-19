<?php

declare(strict_types=1);

namespace DleApi\Tests\Integration;

/**
 * GET /.well-known/oauth-authorization-server — публичный discovery-документ.
 */
final class OAuthDiscoveryTest extends ApiTestCase
{
	private array $document;

	protected function setUp(): void
	{
		$resp           = self::http()->get('.well-known/oauth-authorization-server');
		$this->document = self::json($resp);
	}

	public function testReturns200(): void
	{
		$resp = self::http()->get('.well-known/oauth-authorization-server');

		$this->assertSame(200, $resp->getStatusCode());
	}

	public function testContainsIssuer(): void
	{
		$this->assertArrayHasKey('issuer', $this->document);
		$this->assertNotEmpty($this->document['issuer']);
	}

	public function testContainsTokenEndpoint(): void
	{
		$this->assertArrayHasKey('token_endpoint', $this->document);
		$this->assertStringContainsString('/oauth/token', (string) $this->document['token_endpoint']);
	}

	public function testContainsAuthorizationEndpoint(): void
	{
		$this->assertArrayHasKey('authorization_endpoint', $this->document);
		$this->assertStringContainsString('/oauth/authorize', (string) $this->document['authorization_endpoint']);
	}

	public function testContainsRevocationEndpoint(): void
	{
		$this->assertArrayHasKey('revocation_endpoint', $this->document);
	}

	public function testGrantTypesSupported(): void
	{
		$this->assertArrayHasKey('grant_types_supported', $this->document);
		$this->assertContains('authorization_code', (array) $this->document['grant_types_supported']);
	}
}
