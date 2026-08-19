<?php

declare(strict_types=1);

/**
 * Вспомогательные функции API v2.
 */

require_once DLEPlugins::Check(dirname(__DIR__, 2) . '/Fluent/DcDatabase.php');

/**
 * Парсит CSV категорий в массив id.
 *
 * @return list<int>
 */
function dle_api_parse_categories(string $raw): array {
	$ids = [];
	foreach(explode(',', $raw) as $part) {
		$id = (int) trim($part);
		if($id > 0) {
			$ids[] = $id;
		}
	}

	return array_values(array_unique($ids));
}

/**
 * Читает схему xfields из JSON (DLE 18.1+).
 *
 * @return array<string, mixed>
 */
function dle_api_xfields_schema(): array {
	$path = ENGINE_DIR . '/data/xfields.json';
	if(!is_file($path)) {
		$legacy = ENGINE_DIR . '/data/xfields.txt';
		if(is_file($legacy)) {
			return ['legacy' => true, 'path' => $legacy];
		}

		return [];
	}

	$data = json_decode((string) file_get_contents($path), true);

	return is_array($data) ? $data : [];
}

/**
 * Разбирает строку xfields новости.
 *
 * @return array<string, string>
 */
function dle_api_parse_xfields(string $fields): array {
	$out = [];
	if($fields === '') {
		return $out;
	}
	foreach(explode('||', $fields) as $chunk) {
		if(!str_contains($chunk, '|')) {
			continue;
		}
		[$name, $value] = explode('|', $chunk, 2);
		$out[$name] = $value;
	}

	return $out;
}
