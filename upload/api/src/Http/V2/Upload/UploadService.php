<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Upload;

use DevCraft\Core\Http\UploadedFile;
use DLEPlugins;

/**
 * Загрузка файлов в uploads/ через DevCraft UploadedFile.
 */
final class UploadService {

	/**
	 * @param array{tmp_name?: string, name?: string, error?: int, size?: int, type?: string} $file
	 *
	 * @return array{path: string, url: string, name: string}
	 */
	public function store(array $file, string $subdir = 'files'): array {
		$upload = UploadedFile::fromArray($file);
		$orig   = $upload->originalName();
		$ext    = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
		$safe   = bin2hex(random_bytes(8)) . ($ext !== '' ? '.' . $ext : '');
		$base   = ROOT_DIR . '/uploads/' . trim($subdir, '/');
		$dest   = $base . '/' . $safe;
		$upload->moveTo($dest);

		if(class_exists('DLEFiles', false) || is_file(ENGINE_DIR . '/classes/filesystem.class.php')) {
			if(!class_exists('DLEFiles', false)) {
				require_once DLEPlugins::Check(ENGINE_DIR . '/classes/filesystem.class.php');
			}
		}

		$rel = 'uploads/' . trim($subdir, '/') . '/' . $safe;

		return [
			'path' => $rel,
			'url'  => '/' . $rel,
			'name' => $orig,
		];
	}

}
