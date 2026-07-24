<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `vote`.
 */
#[OA\Schema(schema: 'Vote')]
final class VoteSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (vote.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'category',
		type: 'string',
		description: 'CSV id или all (таблица vote.category)',
	)]
	public string $category = '';
	#[OA\Property(
		property: 'vote_num',
		type: 'integer',
		description: 'Колонка vote.vote_num',
	)]
	public int $vote_num = 0;
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (vote.date)',
	)]
	public int $date = 0;
	#[OA\Property(
		property: 'title',
		type: 'string',
		description: 'Заголовок (vote.title)',
	)]
	public string $title = '';
	#[OA\Property(
		property: 'body',
		type: 'string',
		description: 'Колонка vote.body',
	)]
	public string $body = '';
	#[OA\Property(
		property: 'approve',
		type: 'integer',
		description: 'Одобрено (0/1) (vote.approve)',
	)]
	public int $approve = 1;
	#[OA\Property(
		property: 'start',
		type: 'string',
		description: 'Колонка vote.start',
	)]
	public string $start = '';
	#[OA\Property(
		property: 'end',
		type: 'string',
		description: 'Колонка vote.end',
	)]
	public string $end = '';
	#[OA\Property(
		property: 'grouplevel',
		type: 'string',
		description: 'CSV id или all (таблица vote.grouplevel)',
	)]
	public string $grouplevel = 'all';

	public function table(): string {
		return 'vote';
	}

	protected function columnList(): array {
		return [
			'id',
			'category',
			'vote_num',
			'date',
			'title',
			'body',
			'approve',
			'start',
			'end',
			'grouplevel',
		];
	}

	protected function defaultMap(): array {
		return [
			'category' => '',
			'vote_num' => 0,
			'date' => 0,
			'title' => '',
			'body' => '',
			'approve' => 1,
			'start' => '',
			'end' => '',
			'grouplevel' => 'all',
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
 * Схема таблицы `vote` (DLE install.php).
 */
#[OA\Schema(schema: 'Vote')]
final class VoteSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (vote.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'category',
		type: 'string',
		description: 'CSV id или all (таблица vote.category)',
	)]
	public string $category = '';
	#[OA\Property(
		property: 'vote_num',
		type: 'integer',
		description: 'Колонка vote.vote_num',
	)]
	public int $vote_num = 0;
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (vote.date)',
	)]
	public int $date = 0;
	#[OA\Property(
		property: 'title',
		type: 'string',
		description: 'Заголовок (vote.title)',
	)]
	public string $title = '';
	#[OA\Property(
		property: 'body',
		type: 'string',
		description: 'Колонка vote.body',
	)]
	public string $body = '';
	#[OA\Property(
		property: 'approve',
		type: 'integer',
		description: 'Одобрено (0/1) (vote.approve)',
	)]
	public int $approve = 1;
	#[OA\Property(
		property: 'start',
		type: 'string',
		description: 'Колонка vote.start',
	)]
	public string $start = '';
	#[OA\Property(
		property: 'end',
		type: 'string',
		description: 'Колонка vote.end',
	)]
	public string $end = '';
	#[OA\Property(
		property: 'grouplevel',
		type: 'string',
		description: 'CSV id или all (таблица vote.grouplevel)',
	)]
	public string $grouplevel = 'all';

	public function table(): string {
		return 'vote';
	}

	protected function columnList(): array {
		return [
			'id',
			'category',
			'vote_num',
			'date',
			'title',
			'body',
			'approve',
			'start',
			'end',
			'grouplevel',
		];
	}

	protected function defaultMap(): array {
		return [
			'category' => '',
			'vote_num' => 0,
			'date' => 0,
			'title' => '',
			'body' => '',
			'approve' => 1,
			'start' => '',
			'end' => '',
			'grouplevel' => 'all',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
