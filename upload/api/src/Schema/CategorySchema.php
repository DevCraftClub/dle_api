<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `category`.
 */
#[OA\Schema(schema: 'Category')]
final class CategorySchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (category.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'parentid',
		type: 'integer',
		description: 'Колонка category.parentid',
	)]
	public int $parentid = 0;
	#[OA\Property(
		property: 'posi',
		type: 'integer',
		description: 'Колонка category.posi',
	)]
	public int $posi = 1;
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (category.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'alt_name',
		type: 'string',
		description: 'ЧПУ-имя (category.alt_name)',
	)]
	public string $alt_name = '';
	#[OA\Property(
		property: 'icon',
		type: 'string',
		description: 'Колонка category.icon',
	)]
	public string $icon = '';
	#[OA\Property(
		property: 'skin',
		type: 'string',
		description: 'Колонка category.skin',
	)]
	public string $skin = '';
	#[OA\Property(
		property: 'descr',
		type: 'string',
		description: 'Описание (category.descr)',
	)]
	public string $descr = '';
	#[OA\Property(
		property: 'keywords',
		type: 'string',
		description: 'Ключевые слова (category.keywords)',
	)]
	public string $keywords = '';
	#[OA\Property(
		property: 'news_sort',
		type: 'string',
		description: 'Колонка category.news_sort',
	)]
	public string $news_sort = '';
	#[OA\Property(
		property: 'news_msort',
		type: 'string',
		description: 'Колонка category.news_msort',
	)]
	public string $news_msort = '';
	#[OA\Property(
		property: 'news_number',
		type: 'integer',
		description: 'Колонка category.news_number',
	)]
	public int $news_number = 0;
	#[OA\Property(
		property: 'short_tpl',
		type: 'string',
		description: 'Колонка category.short_tpl',
	)]
	public string $short_tpl = '';
	#[OA\Property(
		property: 'full_tpl',
		type: 'string',
		description: 'Колонка category.full_tpl',
	)]
	public string $full_tpl = '';
	#[OA\Property(
		property: 'metatitle',
		type: 'string',
		description: 'Колонка category.metatitle',
	)]
	public string $metatitle = '';
	#[OA\Property(
		property: 'show_sub',
		type: 'integer',
		description: 'Колонка category.show_sub',
	)]
	public int $show_sub = 0;
	#[OA\Property(
		property: 'allow_rss',
		type: 'integer',
		description: 'Колонка category.allow_rss',
	)]
	public int $allow_rss = 1;
	#[OA\Property(
		property: 'fulldescr',
		type: 'string',
		description: 'Колонка category.fulldescr',
	)]
	public string $fulldescr = '';
	#[OA\Property(
		property: 'disable_search',
		type: 'integer',
		description: 'Колонка category.disable_search',
	)]
	public int $disable_search = 0;
	#[OA\Property(
		property: 'disable_main',
		type: 'integer',
		description: 'Колонка category.disable_main',
	)]
	public int $disable_main = 0;
	#[OA\Property(
		property: 'disable_rating',
		type: 'integer',
		description: 'Колонка category.disable_rating',
	)]
	public int $disable_rating = 0;
	#[OA\Property(
		property: 'disable_comments',
		type: 'integer',
		description: 'CSV id или all (таблица category.disable_comments)',
	)]
	public int $disable_comments = 0;
	#[OA\Property(
		property: 'enable_dzen',
		type: 'integer',
		description: 'Колонка category.enable_dzen',
	)]
	public int $enable_dzen = 1;
	#[OA\Property(
		property: 'active',
		type: 'integer',
		description: 'Колонка category.active',
	)]
	public int $active = 1;
	#[OA\Property(
		property: 'rating_type',
		type: 'integer',
		description: 'Колонка category.rating_type',
	)]
	public int $rating_type = -1;
	#[OA\Property(
		property: 'schema_org',
		type: 'integer',
		description: 'Колонка category.schema_org',
	)]
	public int $schema_org = 1;
	#[OA\Property(
		property: 'disable_index',
		type: 'integer',
		description: 'Колонка category.disable_index',
	)]
	public int $disable_index = 0;

	public function table(): string {
		return 'category';
	}

	protected function columnList(): array {
		return [
			'id',
			'parentid',
			'posi',
			'name',
			'alt_name',
			'icon',
			'skin',
			'descr',
			'keywords',
			'news_sort',
			'news_msort',
			'news_number',
			'short_tpl',
			'full_tpl',
			'metatitle',
			'show_sub',
			'allow_rss',
			'fulldescr',
			'disable_search',
			'disable_main',
			'disable_rating',
			'disable_comments',
			'enable_dzen',
			'active',
			'rating_type',
			'schema_org',
			'disable_index',
		];
	}

	protected function defaultMap(): array {
		return [
			'parentid' => 0,
			'posi' => 1,
			'name' => '',
			'alt_name' => '',
			'icon' => '',
			'skin' => '',
			'descr' => '',
			'keywords' => '',
			'news_sort' => '',
			'news_msort' => '',
			'news_number' => 0,
			'short_tpl' => '',
			'full_tpl' => '',
			'metatitle' => '',
			'show_sub' => 0,
			'allow_rss' => 1,
			'fulldescr' => '',
			'disable_search' => 0,
			'disable_main' => 0,
			'disable_rating' => 0,
			'disable_comments' => 0,
			'enable_dzen' => 1,
			'active' => 1,
			'rating_type' => -1,
			'schema_org' => 1,
			'disable_index' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
