<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `login_log`.
 */
#[OA\Schema(schema: 'LoginLog')]
final class LoginLogSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (login_log.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (login_log.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'count',
		type: 'integer',
		description: 'Колонка login_log.count',
	)]
	public int $count = 0;
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (login_log.date)',
	)]
	public int $date = 0;

	public function table(): string {
		return 'login_log';
	}

	protected function columnList(): array {
		return [
			'id',
			'ip',
			'count',
			'date',
		];
	}

	protected function defaultMap(): array {
		return [
			'ip' => '',
			'count' => 0,
			'date' => 0,
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
 * Схема таблицы `login_log` (DLE install.php).
 */
#[OA\Schema(schema: 'LoginLog')]
final class LoginLogSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (login_log.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (login_log.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'count',
		type: 'integer',
		description: 'Колонка login_log.count',
	)]
	public int $count = 0;
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (login_log.date)',
	)]
	public int $date = 0;

	public function table(): string {
		return 'login_log';
	}

	protected function columnList(): array {
		return [
			'id',
			'ip',
			'count',
			'date',
		];
	}

	protected function defaultMap(): array {
		return [
			'ip' => '',
			'count' => 0,
			'date' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
