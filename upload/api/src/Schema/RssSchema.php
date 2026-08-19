<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `rss`.
 */
#[OA\Schema(schema: 'Rss')]
final class RssSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (rss.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'url',
		type: 'string',
		description: 'Колонка rss.url',
	)]
	public string $url = '';
	#[OA\Property(
		property: 'description',
		type: 'string',
		description: 'Колонка rss.description',
	)]
	public string $description = '';
	#[OA\Property(
		property: 'allow_main',
		type: 'integer',
		description: 'Колонка rss.allow_main',
	)]
	public int $allow_main = 0;
	#[OA\Property(
		property: 'allow_rating',
		type: 'integer',
		description: 'Колонка rss.allow_rating',
	)]
	public int $allow_rating = 0;
	#[OA\Property(
		property: 'allow_comm',
		type: 'integer',
		description: 'Колонка rss.allow_comm',
	)]
	public int $allow_comm = 0;
	#[OA\Property(
		property: 'text_type',
		type: 'integer',
		description: 'Колонка rss.text_type',
	)]
	public int $text_type = 0;
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (rss.date)',
	)]
	public int $date = 0;
	#[OA\Property(
		property: 'search',
		type: 'string',
		description: 'Колонка rss.search',
	)]
	public string $search = '';
	#[OA\Property(
		property: 'max_news',
		type: 'integer',
		description: 'Колонка rss.max_news',
	)]
	public int $max_news = 0;
	#[OA\Property(
		property: 'cookie',
		type: 'string',
		description: 'Колонка rss.cookie',
	)]
	public string $cookie = '';
	#[OA\Property(
		property: 'category',
		type: 'string',
		description: 'CSV id или all (таблица rss.category)',
	)]
	public string $category = '';
	#[OA\Property(
		property: 'lastdate',
		type: 'integer',
		description: 'Колонка rss.lastdate',
	)]
	public int $lastdate = 0;
	#[OA\Property(
		property: 'allow_source',
		type: 'integer',
		description: 'Колонка rss.allow_source',
	)]
	public int $allow_source = 0;

	public function table(): string {
		return 'rss';
	}

	protected function columnList(): array {
		return [
			'id',
			'url',
			'description',
			'allow_main',
			'allow_rating',
			'allow_comm',
			'text_type',
			'date',
			'search',
			'max_news',
			'cookie',
			'category',
			'lastdate',
			'allow_source',
		];
	}

	protected function defaultMap(): array {
		return [
			'url' => '',
			'description' => '',
			'allow_main' => 0,
			'allow_rating' => 0,
			'allow_comm' => 0,
			'text_type' => 0,
			'date' => 0,
			'search' => '',
			'max_news' => 0,
			'cookie' => '',
			'category' => '',
			'lastdate' => 0,
			'allow_source' => 0,
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
 * Схема таблицы `rss`.
 */
#[OA\Schema(schema: 'Rss')]
final class RssSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (rss.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'url',
		type: 'string',
		description: 'Колонка rss.url',
	)]
	public string $url = '';
	#[OA\Property(
		property: 'description',
		type: 'string',
		description: 'Колонка rss.description',
	)]
	public string $description = '';
	#[OA\Property(
		property: 'allow_main',
		type: 'integer',
		description: 'Колонка rss.allow_main',
	)]
	public int $allow_main = 0;
	#[OA\Property(
		property: 'allow_rating',
		type: 'integer',
		description: 'Колонка rss.allow_rating',
	)]
	public int $allow_rating = 0;
	#[OA\Property(
		property: 'allow_comm',
		type: 'integer',
		description: 'Колонка rss.allow_comm',
	)]
	public int $allow_comm = 0;
	#[OA\Property(
		property: 'text_type',
		type: 'integer',
		description: 'Колонка rss.text_type',
	)]
	public int $text_type = 0;
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (rss.date)',
	)]
	public int $date = 0;
	#[OA\Property(
		property: 'search',
		type: 'string',
		description: 'Колонка rss.search',
	)]
	public string $search = '';
	#[OA\Property(
		property: 'max_news',
		type: 'integer',
		description: 'Колонка rss.max_news',
	)]
	public int $max_news = 0;
	#[OA\Property(
		property: 'cookie',
		type: 'string',
		description: 'Колонка rss.cookie',
	)]
	public string $cookie = '';
	#[OA\Property(
		property: 'category',
		type: 'string',
		description: 'CSV id или all (таблица rss.category)',
	)]
	public string $category = '';
	#[OA\Property(
		property: 'lastdate',
		type: 'integer',
		description: 'Колонка rss.lastdate',
	)]
	public int $lastdate = 0;
	#[OA\Property(
		property: 'allow_source',
		type: 'integer',
		description: 'Колонка rss.allow_source',
	)]
	public int $allow_source = 0;

	public function table(): string {
		return 'rss';
	}

	protected function columnList(): array {
		return [
			'id',
			'url',
			'description',
			'allow_main',
			'allow_rating',
			'allow_comm',
			'text_type',
			'date',
			'search',
			'max_news',
			'cookie',
			'category',
			'lastdate',
			'allow_source',
		];
	}

	protected function defaultMap(): array {
		return [
			'url' => '',
			'description' => '',
			'allow_main' => 0,
			'allow_rating' => 0,
			'allow_comm' => 0,
			'text_type' => 0,
			'date' => 0,
			'search' => '',
			'max_news' => 0,
			'cookie' => '',
			'category' => '',
			'lastdate' => 0,
			'allow_source' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
