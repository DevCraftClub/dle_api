<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `post_log`.
 */
#[OA\Schema(schema: 'PostLog')]
final class PostLogSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (post_log.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (post_log.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'expires',
		type: 'string',
		description: 'Колонка post_log.expires',
	)]
	public string $expires = '';
	#[OA\Property(
		property: 'action',
		type: 'integer',
		description: 'Колонка post_log.action',
	)]
	public int $action = 0;
	#[OA\Property(
		property: 'move_cat',
		type: 'string',
		description: 'Колонка post_log.move_cat',
	)]
	public string $move_cat = '';

	public function table(): string {
		return 'post_log';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
			'expires',
			'action',
			'move_cat',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
			'expires' => '',
			'action' => 0,
			'move_cat' => '',
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
 * Схема таблицы `post_log`.
 */
#[OA\Schema(schema: 'PostLog')]
final class PostLogSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (post_log.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (post_log.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'expires',
		type: 'string',
		description: 'Колонка post_log.expires',
	)]
	public string $expires = '';
	#[OA\Property(
		property: 'action',
		type: 'integer',
		description: 'Колонка post_log.action',
	)]
	public int $action = 0;
	#[OA\Property(
		property: 'move_cat',
		type: 'string',
		description: 'Колонка post_log.move_cat',
	)]
	public string $move_cat = '';

	public function table(): string {
		return 'post_log';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
			'expires',
			'action',
			'move_cat',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
			'expires' => '',
			'action' => 0,
			'move_cat' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
