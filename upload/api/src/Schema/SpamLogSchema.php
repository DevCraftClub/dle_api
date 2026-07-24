<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `spam_log` (DLE install.php).
 */
#[OA\Schema(schema: 'SpamLog')]
final class SpamLogSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (spam_log.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (spam_log.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'is_spammer',
		type: 'integer',
		description: 'Колонка spam_log.is_spammer',
	)]
	public int $is_spammer = 0;
	#[OA\Property(
		property: 'email',
		type: 'string',
		description: 'E-mail (spam_log.email)',
	)]
	public string $email = '';
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (spam_log.date)',
	)]
	public int $date = 0;

	public function table(): string {
		return 'spam_log';
	}

	protected function columnList(): array {
		return [
			'id',
			'ip',
			'is_spammer',
			'email',
			'date',
		];
	}

	protected function defaultMap(): array {
		return [
			'ip' => '',
			'is_spammer' => 0,
			'email' => '',
			'date' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
