<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `metatags`.
 */
#[OA\Schema(schema: 'Metatags')]
final class MetatagsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (metatags.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'url',
		type: 'string',
		description: 'Колонка metatags.url',
	)]
	public string $url = '';
	#[OA\Property(
		property: 'title',
		type: 'string',
		description: 'Заголовок (metatags.title)',
	)]
	public string $title = '';
	#[OA\Property(
		property: 'description',
		type: 'string',
		description: 'Колонка metatags.description',
	)]
	public string $description = '';
	#[OA\Property(
		property: 'keywords',
		type: 'string',
		description: 'Ключевые слова (metatags.keywords)',
	)]
	public string $keywords = '';
	#[OA\Property(
		property: 'page_title',
		type: 'string',
		description: 'Колонка metatags.page_title',
	)]
	public string $page_title = '';
	#[OA\Property(
		property: 'page_description',
		type: 'string',
		description: 'Колонка metatags.page_description',
	)]
	public string $page_description = '';
	#[OA\Property(
		property: 'robots',
		type: 'string',
		description: 'Колонка metatags.robots',
	)]
	public string $robots = '';
	#[OA\Property(
		property: 'enabled',
		type: 'integer',
		description: 'Колонка metatags.enabled',
	)]
	public int $enabled = 1;

	public function table(): string {
		return 'metatags';
	}

	protected function columnList(): array {
		return [
			'id',
			'url',
			'title',
			'description',
			'keywords',
			'page_title',
			'page_description',
			'robots',
			'enabled',
		];
	}

	protected function defaultMap(): array {
		return [
			'url' => '',
			'title' => '',
			'description' => '',
			'keywords' => '',
			'page_title' => '',
			'page_description' => '',
			'robots' => '',
			'enabled' => 1,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
