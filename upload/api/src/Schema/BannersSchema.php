<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `banners`.
 */
#[OA\Schema(schema: 'Banners')]
final class BannersSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (banners.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'banner_tag',
		type: 'string',
		description: 'Колонка banners.banner_tag',
	)]
	public string $banner_tag = '';
	#[OA\Property(
		property: 'descr',
		type: 'string',
		description: 'Описание (banners.descr)',
	)]
	public string $descr = '';
	#[OA\Property(
		property: 'code',
		type: 'string',
		description: 'Колонка banners.code',
	)]
	public string $code = '';
	#[OA\Property(
		property: 'approve',
		type: 'integer',
		description: 'Одобрено (0/1) (banners.approve)',
	)]
	public int $approve = 0;
	#[OA\Property(
		property: 'short_place',
		type: 'integer',
		description: 'Колонка banners.short_place',
	)]
	public int $short_place = 0;
	#[OA\Property(
		property: 'bstick',
		type: 'integer',
		description: 'Колонка banners.bstick',
	)]
	public int $bstick = 0;
	#[OA\Property(
		property: 'main',
		type: 'integer',
		description: 'Колонка banners.main',
	)]
	public int $main = 0;
	#[OA\Property(
		property: 'category',
		type: 'string',
		description: 'CSV id категорий (virtual FK csv → category.id)',
		x: ['dle-ref' => 'category.id', 'dle-kind' => 'csv'],
	)]
	public string $category = '';
	#[OA\Property(
		property: 'grouplevel',
		type: 'string',
		description: 'CSV групп или all (virtual FK csv_or_all → usergroups.id)',
		x: ['dle-ref' => 'usergroups.id', 'dle-kind' => 'csv_or_all'],
	)]
	public string $grouplevel = 'all';
	#[OA\Property(
		property: 'rubric',
		type: 'integer',
		description: 'Virtual FK one → banners_rubrics.id',
		x: ['dle-ref' => 'banners_rubrics.id', 'dle-kind' => 'one'],
	)]
	public int $rubric = 0;
	#[OA\Property(
		property: 'start',
		type: 'string',
		description: 'Колонка banners.start',
	)]
	public string $start = '';
	#[OA\Property(
		property: 'end',
		type: 'string',
		description: 'Колонка banners.end',
	)]
	public string $end = '';
	#[OA\Property(
		property: 'fpage',
		type: 'integer',
		description: 'Колонка banners.fpage',
	)]
	public int $fpage = 0;
	#[OA\Property(
		property: 'innews',
		type: 'integer',
		description: 'Колонка banners.innews',
	)]
	public int $innews = 0;
	#[OA\Property(
		property: 'devicelevel',
		type: 'string',
		description: 'Колонка banners.devicelevel',
	)]
	public string $devicelevel = '';
	#[OA\Property(
		property: 'allow_views',
		type: 'integer',
		description: 'Колонка banners.allow_views',
	)]
	public int $allow_views = 0;
	#[OA\Property(
		property: 'max_views',
		type: 'integer',
		description: 'Колонка banners.max_views',
	)]
	public int $max_views = 0;
	#[OA\Property(
		property: 'allow_counts',
		type: 'integer',
		description: 'Колонка banners.allow_counts',
	)]
	public int $allow_counts = 0;
	#[OA\Property(
		property: 'max_counts',
		type: 'integer',
		description: 'Колонка banners.max_counts',
	)]
	public int $max_counts = 0;
	#[OA\Property(
		property: 'views',
		type: 'integer',
		description: 'Колонка banners.views',
	)]
	public int $views = 0;
	#[OA\Property(
		property: 'clicks',
		type: 'integer',
		description: 'Колонка banners.clicks',
	)]
	public int $clicks = 0;
	#[OA\Property(
		property: 'comments_place',
		type: 'integer',
		description: 'Колонка banners.comments_place',
	)]
	public int $comments_place = 0;
	#[OA\Property(
		property: 'allowed_country',
		type: 'string',
		description: 'Колонка banners.allowed_country',
	)]
	public string $allowed_country = '';
	#[OA\Property(
		property: 'not_allowed_country',
		type: 'string',
		description: 'Колонка banners.not_allowed_country',
	)]
	public string $not_allowed_country = '';

	public function table(): string {
		return 'banners';
	}

	protected function columnList(): array {
		return [
			'id',
			'banner_tag',
			'descr',
			'code',
			'approve',
			'short_place',
			'bstick',
			'main',
			'category',
			'grouplevel',
			'start',
			'end',
			'fpage',
			'innews',
			'devicelevel',
			'allow_views',
			'max_views',
			'allow_counts',
			'max_counts',
			'views',
			'clicks',
			'rubric',
			'comments_place',
			'allowed_country',
			'not_allowed_country',
		];
	}

	protected function defaultMap(): array {
		return [
			'banner_tag' => '',
			'descr' => '',
			'code' => '',
			'approve' => 0,
			'short_place' => 0,
			'bstick' => 0,
			'main' => 0,
			'category' => '',
			'grouplevel' => 'all',
			'start' => '',
			'end' => '',
			'fpage' => 0,
			'innews' => 0,
			'devicelevel' => '',
			'allow_views' => 0,
			'max_views' => 0,
			'allow_counts' => 0,
			'max_counts' => 0,
			'views' => 0,
			'clicks' => 0,
			'rubric' => 0,
			'comments_place' => 0,
			'allowed_country' => '',
			'not_allowed_country' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
