<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `tags`.
 */
#[OA\Schema(schema: 'Tags')]
final class TagsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (tags.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (tags.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'tag',
		type: 'string',
		description: 'Колонка tags.tag',
	)]
	public string $tag = '';

	public function table(): string {
		return 'tags';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
			'tag',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
			'tag' => '',
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
 * Схема таблицы `tags`.
 */
#[OA\Schema(schema: 'Tags')]
final class TagsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (tags.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (tags.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'tag',
		type: 'string',
		description: 'Колонка tags.tag',
	)]
	public string $tag = '';

	public function table(): string {
		return 'tags';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
			'tag',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
			'tag' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
