<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `rssinform`.
 */
#[OA\Schema(schema: 'Rssinform')]
final class RssinformSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (rssinform.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'tag',
		type: 'string',
		description: 'Колонка rssinform.tag',
	)]
	public string $tag = '';
	#[OA\Property(
		property: 'descr',
		type: 'string',
		description: 'Описание (rssinform.descr)',
	)]
	public string $descr = '';
	#[OA\Property(
		property: 'category',
		type: 'string',
		description: 'CSV id или all (таблица rssinform.category)',
	)]
	public string $category = '';
	#[OA\Property(
		property: 'url',
		type: 'string',
		description: 'Колонка rssinform.url',
	)]
	public string $url = '';
	#[OA\Property(
		property: 'template',
		type: 'string',
		description: 'Колонка rssinform.template',
	)]
	public string $template = '';
	#[OA\Property(
		property: 'news_max',
		type: 'integer',
		description: 'Колонка rssinform.news_max',
	)]
	public int $news_max = 0;
	#[OA\Property(
		property: 'tmax',
		type: 'integer',
		description: 'Колонка rssinform.tmax',
	)]
	public int $tmax = 0;
	#[OA\Property(
		property: 'dmax',
		type: 'integer',
		description: 'Колонка rssinform.dmax',
	)]
	public int $dmax = 0;
	#[OA\Property(
		property: 'approve',
		type: 'integer',
		description: 'Одобрено (0/1) (rssinform.approve)',
	)]
	public int $approve = 1;
	#[OA\Property(
		property: 'rss_date_format',
		type: 'string',
		description: 'Колонка rssinform.rss_date_format',
	)]
	public string $rss_date_format = '';

	public function table(): string {
		return 'rssinform';
	}

	protected function columnList(): array {
		return [
			'id',
			'tag',
			'descr',
			'category',
			'url',
			'template',
			'news_max',
			'tmax',
			'dmax',
			'approve',
			'rss_date_format',
		];
	}

	protected function defaultMap(): array {
		return [
			'tag' => '',
			'descr' => '',
			'category' => '',
			'url' => '',
			'template' => '',
			'news_max' => 0,
			'tmax' => 0,
			'dmax' => 0,
			'approve' => 1,
			'rss_date_format' => '',
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
 * Схема таблицы `rssinform`.
 */
#[OA\Schema(schema: 'Rssinform')]
final class RssinformSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (rssinform.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'tag',
		type: 'string',
		description: 'Колонка rssinform.tag',
	)]
	public string $tag = '';
	#[OA\Property(
		property: 'descr',
		type: 'string',
		description: 'Описание (rssinform.descr)',
	)]
	public string $descr = '';
	#[OA\Property(
		property: 'category',
		type: 'string',
		description: 'CSV id или all (таблица rssinform.category)',
	)]
	public string $category = '';
	#[OA\Property(
		property: 'url',
		type: 'string',
		description: 'Колонка rssinform.url',
	)]
	public string $url = '';
	#[OA\Property(
		property: 'template',
		type: 'string',
		description: 'Колонка rssinform.template',
	)]
	public string $template = '';
	#[OA\Property(
		property: 'news_max',
		type: 'integer',
		description: 'Колонка rssinform.news_max',
	)]
	public int $news_max = 0;
	#[OA\Property(
		property: 'tmax',
		type: 'integer',
		description: 'Колонка rssinform.tmax',
	)]
	public int $tmax = 0;
	#[OA\Property(
		property: 'dmax',
		type: 'integer',
		description: 'Колонка rssinform.dmax',
	)]
	public int $dmax = 0;
	#[OA\Property(
		property: 'approve',
		type: 'integer',
		description: 'Одобрено (0/1) (rssinform.approve)',
	)]
	public int $approve = 1;
	#[OA\Property(
		property: 'rss_date_format',
		type: 'string',
		description: 'Колонка rssinform.rss_date_format',
	)]
	public string $rss_date_format = '';

	public function table(): string {
		return 'rssinform';
	}

	protected function columnList(): array {
		return [
			'id',
			'tag',
			'descr',
			'category',
			'url',
			'template',
			'news_max',
			'tmax',
			'dmax',
			'approve',
			'rss_date_format',
		];
	}

	protected function defaultMap(): array {
		return [
			'tag' => '',
			'descr' => '',
			'category' => '',
			'url' => '',
			'template' => '',
			'news_max' => 0,
			'tmax' => 0,
			'dmax' => 0,
			'approve' => 1,
			'rss_date_format' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
