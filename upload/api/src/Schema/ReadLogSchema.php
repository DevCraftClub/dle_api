<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `read_log` (DLE install.php).
 */
#[OA\Schema(schema: 'ReadLog')]
final class ReadLogSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (read_log.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (read_log.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (read_log.ip)',
	)]
	public string $ip = '';

	public function table(): string {
		return 'read_log';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
			'ip',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
			'ip' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
