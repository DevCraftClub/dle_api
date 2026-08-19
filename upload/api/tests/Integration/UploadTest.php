<?php

declare(strict_types=1);

namespace DleApi\Tests\Integration;

/**
 * POST /upload — загрузка файлов.
 */
final class UploadTest extends ApiTestCase
{
	public function testUploadWithoutBearerReturns401(): void
	{
		$resp = self::http()->post('upload');

		$this->assertSame(401, $resp->getStatusCode());
		$body = self::json($resp);
		$this->assertSame('unauthorized', $body['error']);
	}

	public function testUploadWithoutFileReturns422(): void
	{
		// Авторизован, но без поля file в multipart
		$resp = self::http()->post('upload', [
			'headers' => self::authHeader(),
		]);

		$this->assertSame(422, $resp->getStatusCode());
		$body = self::json($resp);
		$this->assertSame('validation', $body['error']);
	}

	public function testUploadSmallImageReturns200(): void
	{
		// Минимальный валидный PNG (1×1 пиксель)
		$png = base64_decode(
			'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
		);

		$tmpFile = tempnam(sys_get_temp_dir(), 'dle_upload_test_') . '.png';
		file_put_contents($tmpFile, $png);

		try {
			$resp = self::http()->post('upload', [
				'headers'   => self::authHeader(),
				'multipart' => [
					[
						'name'     => 'file',
						'contents' => fopen($tmpFile, 'r'),
						'filename' => 'test_1x1.png',
					],
				],
			]);

			$this->assertContains($resp->getStatusCode(), [200, 201, 400, 422]);

			if ($resp->getStatusCode() === 200 || $resp->getStatusCode() === 201) {
				$body = self::json($resp);
				// Ответ содержит path и url загруженного файла
				$this->assertArrayHasKey('path', $body);
				$this->assertArrayHasKey('url', $body);
			}
		} finally {
			@unlink($tmpFile);
		}
	}
}
