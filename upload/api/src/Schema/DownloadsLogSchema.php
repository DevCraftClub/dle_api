<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `downloads_log`.
 */
#[OA\Schema(schema: 'DownloadsLog')]
final class DownloadsLogSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (downloads_log.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (downloads_log.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (downloads_log.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'file_id',
		type: 'integer',
		description: 'Колонка downloads_log.file_id',
	)]
	public int $file_id = 0;
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (downloads_log.date)',
	)]
	public int $date = 0;

	public function table(): string {
		return 'downloads_log';
	}

	protected function columnList(): array {
		return [
			'id',
			'user_id',
			'ip',
			'file_id',
			'date',
		];
	}

	protected function defaultMap(): array {
		return [
			'user_id' => 0,
			'ip' => '',
			'file_id' => 0,
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
 * Схема таблицы `downloads_log`.
 */
#[OA\Schema(schema: 'DownloadsLog')]
final class DownloadsLogSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (downloads_log.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (downloads_log.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (downloads_log.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'file_id',
		type: 'integer',
		description: 'Колонка downloads_log.file_id',
	)]
	public int $file_id = 0;
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (downloads_log.date)',
	)]
	public int $date = 0;

	public function table(): string {
		return 'downloads_log';
	}

	protected function columnList(): array {
		return [
			'id',
			'user_id',
			'ip',
			'file_id',
			'date',
		];
	}

	protected function defaultMap(): array {
		return [
			'user_id' => 0,
			'ip' => '',
			'file_id' => 0,
			'date' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
