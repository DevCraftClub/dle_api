<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `ignore_list` (DLE install.php).
 */
#[OA\Schema(schema: 'IgnoreList')]
final class IgnoreListSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (ignore_list.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'user',
		type: 'integer',
		description: 'Колонка ignore_list.user',
	)]
	public int $user = 0;
	#[OA\Property(
		property: 'user_from',
		type: 'string',
		description: 'Колонка ignore_list.user_from',
	)]
	public string $user_from = '';

	public function table(): string {
		return 'ignore_list';
	}

	protected function columnList(): array {
		return [
			'id',
			'user',
			'user_from',
		];
	}

	protected function defaultMap(): array {
		return [
			'user' => 0,
			'user_from' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
