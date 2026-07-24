<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `vote_result` (DLE install.php).
 */
#[OA\Schema(schema: 'VoteResult')]
final class VoteResultSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (vote_result.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (vote_result.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (vote_result.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'vote_id',
		type: 'integer',
		description: 'Колонка vote_result.vote_id',
	)]
	public int $vote_id = 0;
	#[OA\Property(
		property: 'answer',
		type: 'integer',
		description: 'Колонка vote_result.answer',
	)]
	public int $answer = 0;

	public function table(): string {
		return 'vote_result';
	}

	protected function columnList(): array {
		return [
			'id',
			'ip',
			'name',
			'vote_id',
			'answer',
		];
	}

	protected function defaultMap(): array {
		return [
			'ip' => '',
			'name' => '',
			'vote_id' => 0,
			'answer' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
