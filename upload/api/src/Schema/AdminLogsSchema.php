<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `admin_logs`.
 */
#[OA\Schema(schema: 'AdminLogs')]
final class AdminLogsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (admin_logs.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (admin_logs.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (admin_logs.date)',
	)]
	public int $date = 0;
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (admin_logs.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'action',
		type: 'integer',
		description: 'Колонка admin_logs.action',
	)]
	public int $action = 0;
	#[OA\Property(
		property: 'extras',
		type: 'string',
		description: 'Колонка admin_logs.extras',
	)]
	public string $extras = '';

	public function table(): string {
		return 'admin_logs';
	}

	protected function columnList(): array {
		return [
			'id',
			'name',
			'date',
			'ip',
			'action',
			'extras',
		];
	}

	protected function defaultMap(): array {
		return [
			'name' => '',
			'date' => 0,
			'ip' => '',
			'action' => 0,
			'extras' => '',
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
 * Схема таблицы `admin_logs` (DLE install.php).
 */
#[OA\Schema(schema: 'AdminLogs')]
final class AdminLogsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (admin_logs.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (admin_logs.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (admin_logs.date)',
	)]
	public int $date = 0;
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (admin_logs.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'action',
		type: 'integer',
		description: 'Колонка admin_logs.action',
	)]
	public int $action = 0;
	#[OA\Property(
		property: 'extras',
		type: 'string',
		description: 'Колонка admin_logs.extras',
	)]
	public string $extras = '';

	public function table(): string {
		return 'admin_logs';
	}

	protected function columnList(): array {
		return [
			'id',
			'name',
			'date',
			'ip',
			'action',
			'extras',
		];
	}

	protected function defaultMap(): array {
		return [
			'name' => '',
			'date' => 0,
			'ip' => '',
			'action' => 0,
			'extras' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
