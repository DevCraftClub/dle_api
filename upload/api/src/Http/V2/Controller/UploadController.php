<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Controller;

use DleApi\Http\V2\JsonResponder;
use DleApi\Http\V2\Upload\UploadService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

/**
 * Загрузка файлов POST /upload/.
 */
final class UploadController {

	public function __construct(
		private readonly UploadService $upload = new UploadService(),
	) {
	}

	public function upload(Request $request, Response $_response): Response {
		$files = $request->getUploadedFiles();
		$file  = $files['file'] ?? null;
		if($file === null) {
			return JsonResponder::error('validation', __('Нужен multipart field file'), 422);
		}
		$subdir = (string) (($request->getParsedBody()['subdir'] ?? 'files'));
		try {
			$tmpPath = tempnam(sys_get_temp_dir(), 'dleapi');
			if($tmpPath === false) {
				throw new \RuntimeException('Не удалось создать временный файл');
			}
			$file->moveTo($tmpPath);
			$result = $this->upload->store([
				'tmp_name' => $tmpPath,
				'name'     => (string) $file->getClientFilename(),
				'error'    => UPLOAD_ERR_OK,
				'size'     => (int) $file->getSize(),
			], $subdir);
		} catch(Throwable $e) {
			return JsonResponder::error('upload_failed', $e->getMessage(), 422);
		}

		return JsonResponder::ok($result, 201);
	}

}
