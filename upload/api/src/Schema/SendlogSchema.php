<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `sendlog`.
 */
#[OA\Schema(schema: 'Sendlog')]
final class SendlogSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (sendlog.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'user',
		type: 'string',
		description: 'Колонка sendlog.user',
	)]
	public string $user = '';
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (sendlog.date)',
	)]
	public int $date = 0;
	#[OA\Property(
		property: 'flag',
		type: 'integer',
		description: 'Колонка sendlog.flag',
	)]
	public int $flag = 0;

	public function table(): string {
		return 'sendlog';
	}

	protected function columnList(): array {
		return [
			'id',
			'user',
			'date',
			'flag',
		];
	}

	protected function defaultMap(): array {
		return [
			'user' => '',
			'date' => 0,
			'flag' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
