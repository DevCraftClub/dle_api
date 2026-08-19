<?php

declare(strict_types=1);

namespace DleApi\Tests\Integration;

/**
 * GET/POST/PUT/DELETE /table/{name} — CRUD по схемам DLE-таблиц.
 */
final class TableCrudTest extends ApiTestCase
{
	// Таблица users существует и доступна по этому имени
	private const TABLE_USERS = 'users';
	private const TABLE_POST  = 'post';

	public function testListUsersReturns200(): void
	{
		$resp = self::http()->get('table/' . self::TABLE_USERS, [
			'headers' => self::authHeader(),
		]);

		$this->assertSame(200, $resp->getStatusCode());
	}

	public function testListUsersBodyHasDataArray(): void
	{
		$resp = self::http()->get('table/' . self::TABLE_USERS, [
			'headers' => self::authHeader(),
		]);

		$body = self::json($resp);
		$this->assertArrayHasKey('data', $body);
		$this->assertIsArray($body['data']);
	}

	public function testListPostReturns200(): void
	{
		$resp = self::http()->get('table/' . self::TABLE_POST, [
			'headers' => self::authHeader(),
		]);

		$this->assertSame(200, $resp->getStatusCode());
	}

	public function testUnknownTableReturnsError(): void
	{
		$resp = self::http()->get('table/nonexistent_table_xyz', [
			'headers' => self::authHeader(),
		]);

		$this->assertContains($resp->getStatusCode(), [400, 404, 422]);
		$body = self::json($resp);
		$this->assertArrayHasKey('error', $body);
	}

	public function testGetSingleRowReturns200OrNotFound(): void
	{
		// Сначала получаем список, берём первый id
		$listResp = self::http()->get('table/' . self::TABLE_USERS, [
			'headers' => self::authHeader(),
		]);
		$listBody = self::json($listResp);
		$rows     = (array) ($listBody['data'] ?? []);

		if (count($rows) === 0) {
			$this->markTestSkipped('Нет записей в таблице users');
		}

		$firstRow = (array) reset($rows);
		$id       = (int) ($firstRow['user_id'] ?? $firstRow['id'] ?? 0);

		if ($id <= 0) {
			$this->markTestSkipped('Не удалось определить id первой записи');
		}

		$resp = self::http()->get('table/' . self::TABLE_USERS . '/' . $id, [
			'headers' => self::authHeader(),
		]);

		$this->assertContains($resp->getStatusCode(), [200, 404]);
	}

	public function testListWithoutBearerReturns401(): void
	{
		$resp = self::http()->get('table/' . self::TABLE_USERS);

		$this->assertSame(401, $resp->getStatusCode());
	}
}
