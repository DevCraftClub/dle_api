<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `complaint` (DLE install.php).
 */
#[OA\Schema(schema: 'Complaint')]
final class ComplaintSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (complaint.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'p_id',
		type: 'integer',
		description: 'Колонка complaint.p_id',
	)]
	public int $p_id = 0;
	#[OA\Property(
		property: 'c_id',
		type: 'integer',
		description: 'ID комментария (complaint.c_id)',
	)]
	public int $c_id = 0;
	#[OA\Property(
		property: 'n_id',
		type: 'integer',
		description: 'Колонка complaint.n_id',
	)]
	public int $n_id = 0;
	#[OA\Property(
		property: 'text',
		type: 'string',
		description: 'Колонка complaint.text',
	)]
	public string $text = '';
	#[OA\Property(
		property: 'from',
		type: 'string',
		description: 'Колонка complaint.from',
	)]
	public string $from = '';
	#[OA\Property(
		property: 'to',
		type: 'string',
		description: 'Колонка complaint.to',
	)]
	public string $to = '';
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (complaint.date)',
	)]
	public int $date = 0;
	#[OA\Property(
		property: 'email',
		type: 'string',
		description: 'E-mail (complaint.email)',
	)]
	public string $email = '';

	public function table(): string {
		return 'complaint';
	}

	protected function columnList(): array {
		return [
			'id',
			'p_id',
			'c_id',
			'n_id',
			'text',
			'from',
			'to',
			'date',
			'email',
		];
	}

	protected function defaultMap(): array {
		return [
			'p_id' => 0,
			'c_id' => 0,
			'n_id' => 0,
			'text' => '',
			'from' => '',
			'to' => '',
			'date' => 0,
			'email' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
