<?php

declare(strict_types=1);

namespace DleApi\Tests\Integration;

/**
 * GET/POST/PUT/PATCH/DELETE /xfields/{scope} — работа с доп. полями DLE.
 */
final class XfieldTest extends ApiTestCase
{
	public function testListPostXfieldsReturns200(): void
	{
		$resp = self::http()->get('xfields/post', [
			'headers' => self::authHeader(),
		]);

		$this->assertSame(200, $resp->getStatusCode());
	}

	public function testListPostXfieldsBodyHasDataWithFields(): void
	{
		$resp = self::http()->get('xfields/post', [
			'headers' => self::authHeader(),
		]);

		$body = self::json($resp);
		$this->assertArrayHasKey('data', $body);
		$data = (array) $body['data'];
		$this->assertArrayHasKey('fields', $data);
	}

	public function testListUserXfieldsReturns200(): void
	{
		$resp = self::http()->get('xfields/user', [
			'headers' => self::authHeader(),
		]);

		$this->assertSame(200, $resp->getStatusCode());
	}

	public function testGetExistingXfieldReturns200(): void
	{
		// Получаем список и берём первое поле
		$listBody = self::json(self::http()->get('xfields/post', [
			'headers' => self::authHeader(),
		]));

		$fields = (array) (((array) ($listBody['data'] ?? []))['fields'] ?? []);

		if (count($fields) === 0) {
			$this->markTestSkipped('Нет доп. полей для post');
		}

		$firstName = array_key_first($fields);

		$resp = self::http()->get('xfields/post/' . $firstName, [
			'headers' => self::authHeader(),
		]);

		$this->assertSame(200, $resp->getStatusCode());
	}

	public function testGetExistingXfieldBodyHasName(): void
	{
		$listBody = self::json(self::http()->get('xfields/post', [
			'headers' => self::authHeader(),
		]));

		$fields = (array) (((array) ($listBody['data'] ?? []))['fields'] ?? []);

		if (count($fields) === 0) {
			$this->markTestSkipped('Нет доп. полей для post');
		}

		$firstName = array_key_first($fields);

		$resp = self::http()->get('xfields/post/' . $firstName, [
			'headers' => self::authHeader(),
		]);

		$body = self::json($resp);
		$this->assertArrayHasKey('data', $body);
		$fieldData = (array) $body['data'];
		$this->assertArrayHasKey('name', $fieldData);
		$this->assertSame($firstName, $fieldData['name']);
	}

	public function testGetNonExistentXfieldReturns404(): void
	{
		$resp = self::http()->get('xfields/post/nonexistent_field_xyz', [
			'headers' => self::authHeader(),
		]);

		$this->assertSame(404, $resp->getStatusCode());
		$body = self::json($resp);
		$this->assertSame('not_found', $body['error']);
	}

	public function testInvalidScopeReturnsError(): void
	{
		$resp = self::http()->get('xfields/invalid_scope', [
			'headers' => self::authHeader(),
		]);

		$this->assertContains($resp->getStatusCode(), [400, 404, 422]);
	}

	public function testListXfieldsWithoutBearerReturns401(): void
	{
		$resp = self::http()->get('xfields/post');

		$this->assertSame(401, $resp->getStatusCode());
	}
}
