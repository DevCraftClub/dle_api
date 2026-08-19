<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `banners_logs`.
 */
#[OA\Schema(schema: 'BannersLogs')]
final class BannersLogsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (banners_logs.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'bid',
		type: 'integer',
		description: 'Колонка banners_logs.bid',
	)]
	public int $bid = 0;
	#[OA\Property(
		property: 'click',
		type: 'integer',
		description: 'Колонка banners_logs.click',
	)]
	public int $click = 0;
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (banners_logs.ip)',
	)]
	public string $ip = '';

	public function table(): string {
		return 'banners_logs';
	}

	protected function columnList(): array {
		return [
			'id',
			'bid',
			'click',
			'ip',
		];
	}

	protected function defaultMap(): array {
		return [
			'bid' => 0,
			'click' => 0,
			'ip' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
