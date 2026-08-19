<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `poll_log`.
 */
#[OA\Schema(schema: 'PollLog')]
final class PollLogSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (poll_log.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (poll_log.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'member',
		type: 'string',
		description: 'Колонка poll_log.member',
	)]
	public string $member = '';

	public function table(): string {
		return 'poll_log';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
			'member',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
			'member' => '',
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
 * Схема таблицы `poll_log`.
 */
#[OA\Schema(schema: 'PollLog')]
final class PollLogSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (poll_log.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (poll_log.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'member',
		type: 'string',
		description: 'Колонка poll_log.member',
	)]
	public string $member = '';

	public function table(): string {
		return 'poll_log';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
			'member',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
			'member' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
