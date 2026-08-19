<?php

declare(strict_types=1);

namespace DleApi\Tests\Integration;

/**
 * GET /post, GET /post/{id} — список и одиночная новость.
 */
final class PostTest extends ApiTestCase
{
	public function testListPostsReturns200(): void
	{
		$resp = self::http()->get('post', [
			'headers' => self::authHeader(),
		]);

		$this->assertSame(200, $resp->getStatusCode());
	}

	public function testListPostsBodyHasDataArray(): void
	{
		$resp = self::http()->get('post', [
			'headers' => self::authHeader(),
		]);

		$body = self::json($resp);
		$this->assertArrayHasKey('data', $body);
		$this->assertIsArray($body['data']);
	}

	public function testGetExistingPostReturns200(): void
	{
		// Получаем id первой новости из списка
		$listBody = self::json(self::http()->get('post', [
			'headers' => self::authHeader(),
		]));

		$posts = (array) ($listBody['data'] ?? []);
		if (count($posts) === 0) {
			$this->markTestSkipped('Нет новостей в БД');
		}

		$firstPost = (array) reset($posts);
		$id        = (int) ($firstPost['id'] ?? 0);

		if ($id <= 0) {
			$this->markTestSkipped('Не удалось определить id первой новости');
		}

		$resp = self::http()->get('post/' . $id, [
			'headers' => self::authHeader(),
		]);

		$this->assertSame(200, $resp->getStatusCode());
	}

	public function testGetExistingPostBodyHasExpectedFields(): void
	{
		$listBody = self::json(self::http()->get('post', [
			'headers' => self::authHeader(),
		]));

		$posts = (array) ($listBody['data'] ?? []);
		if (count($posts) === 0) {
			$this->markTestSkipped('Нет новостей в БД');
		}

		$firstPost = (array) reset($posts);
		$id        = (int) ($firstPost['id'] ?? 0);

		if ($id <= 0) {
			$this->markTestSkipped('Не удалось определить id первой новости');
		}

		$resp = self::http()->get('post/' . $id, [
			'headers' => self::authHeader(),
		]);

		$body = self::json($resp);
		$this->assertArrayHasKey('data', $body);

		$post = (array) $body['data'];
		$this->assertArrayHasKey('id', $post);
		$this->assertArrayHasKey('autor', $post);
		$this->assertArrayHasKey('date', $post);
	}

	public function testGetNonExistentPostReturns404(): void
	{
		$resp = self::http()->get('post/999999999', [
			'headers' => self::authHeader(),
		]);

		$this->assertSame(404, $resp->getStatusCode());
		$body = self::json($resp);
		$this->assertSame('not_found', $body['error']);
	}

	public function testListPostsWithoutBearerReturns401(): void
	{
		$resp = self::http()->get('post');

		$this->assertSame(401, $resp->getStatusCode());
	}
}
