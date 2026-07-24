<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `banned`.
 */
#[OA\Schema(schema: 'Banned')]
final class BannedSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (banned.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'users_id',
		type: 'integer',
		description: 'Колонка banned.users_id',
	)]
	public int $users_id = 0;
	#[OA\Property(
		property: 'descr',
		type: 'string',
		description: 'Описание (banned.descr)',
	)]
	public string $descr = '';
	#[OA\Property(
		property: 'date',
		type: 'string',
		description: 'Дата/время (banned.date)',
	)]
	public string $date = '';
	#[OA\Property(
		property: 'days',
		type: 'integer',
		description: 'Колонка banned.days',
	)]
	public int $days = 0;
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (banned.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'banned_from',
		type: 'string',
		description: 'Колонка banned.banned_from',
	)]
	public string $banned_from = '';

	public function table(): string {
		return 'banned';
	}

	protected function columnList(): array {
		return [
			'id',
			'users_id',
			'descr',
			'date',
			'days',
			'ip',
			'banned_from',
		];
	}

	protected function defaultMap(): array {
		return [
			'users_id' => 0,
			'descr' => '',
			'date' => '',
			'days' => 0,
			'ip' => '',
			'banned_from' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `banned` (DLE install.php).
 */
#[OA\Schema(schema: 'Banned')]
final class BannedSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (banned.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'users_id',
		type: 'integer',
		description: 'Колонка banned.users_id',
	)]
	public int $users_id = 0;
	#[OA\Property(
		property: 'descr',
		type: 'string',
		description: 'Описание (banned.descr)',
	)]
	public string $descr = '';
	#[OA\Property(
		property: 'date',
		type: 'string',
		description: 'Дата/время (banned.date)',
	)]
	public string $date = '';
	#[OA\Property(
		property: 'days',
		type: 'integer',
		description: 'Колонка banned.days',
	)]
	public int $days = 0;
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (banned.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'banned_from',
		type: 'string',
		description: 'Колонка banned.banned_from',
	)]
	public string $banned_from = '';

	public function table(): string {
		return 'banned';
	}

	protected function columnList(): array {
		return [
			'id',
			'users_id',
			'descr',
			'date',
			'days',
			'ip',
			'banned_from',
		];
	}

	protected function defaultMap(): array {
		return [
			'users_id' => 0,
			'descr' => '',
			'date' => '',
			'days' => 0,
			'ip' => '',
			'banned_from' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
