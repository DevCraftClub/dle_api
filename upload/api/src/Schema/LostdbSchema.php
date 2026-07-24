<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `lostdb` (DLE install.php).
 */
#[OA\Schema(schema: 'Lostdb')]
final class LostdbSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (lostdb.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'lostname',
		type: 'integer',
		description: 'Колонка lostdb.lostname',
	)]
	public int $lostname = 0;
	#[OA\Property(
		property: 'lostid',
		type: 'string',
		description: 'Колонка lostdb.lostid',
	)]
	public string $lostid = '';

	public function table(): string {
		return 'lostdb';
	}

	protected function columnList(): array {
		return [
			'id',
			'lostname',
			'lostid',
		];
	}

	protected function defaultMap(): array {
		return [
			'lostname' => 0,
			'lostid' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
