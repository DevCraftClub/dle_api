<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `flood`.
 */
#[OA\Schema(schema: 'Flood')]
final class FloodSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'f_id',
		type: 'integer',
		description: 'Колонка flood.f_id',
	)]
	public int $f_id = 0;
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (flood.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'id',
		type: 'string',
		description: 'Первичный ключ (flood.id)',
	)]
	public string $id = '';
	#[OA\Property(
		property: 'flag',
		type: 'integer',
		description: 'Колонка flood.flag',
	)]
	public int $flag = 0;

	public function table(): string {
		return 'flood';
	}

	protected function columnList(): array {
		return [
			'f_id',
			'ip',
			'id',
			'flag',
		];
	}

	protected function defaultMap(): array {
		return [
			'ip' => '',
			'id' => '',
			'flag' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'f_id';
	}
}
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `flood` (DLE install.php).
 */
#[OA\Schema(schema: 'Flood')]
final class FloodSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'f_id',
		type: 'integer',
		description: 'Колонка flood.f_id',
	)]
	public int $f_id = 0;
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (flood.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'id',
		type: 'string',
		description: 'Первичный ключ (flood.id)',
	)]
	public string $id = '';
	#[OA\Property(
		property: 'flag',
		type: 'integer',
		description: 'Колонка flood.flag',
	)]
	public int $flag = 0;

	public function table(): string {
		return 'flood';
	}

	protected function columnList(): array {
		return [
			'f_id',
			'ip',
			'id',
			'flag',
		];
	}

	protected function defaultMap(): array {
		return [
			'ip' => '',
			'id' => '',
			'flag' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'f_id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
