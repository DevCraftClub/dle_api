<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `newsletter_template_items` (DLE install.php).
 */
#[OA\Schema(schema: 'NewsletterTemplateItems')]
final class NewsletterTemplateItemsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (newsletter_template_items.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'category_id',
		type: 'integer',
		nullable: true,
		description: 'ID категории шаблона (newsletter_template_items.category_id)',
	)]
	public mixed $category_id = null;
	#[OA\Property(
		property: 'title',
		type: 'string',
		description: 'Заголовок (newsletter_template_items.title)',
	)]
	public string $title = '';
	#[OA\Property(
		property: 'content',
		type: 'string',
		description: 'Колонка newsletter_template_items.content',
	)]
	public string $content = '';
	#[OA\Property(
		property: 'locked',
		type: 'integer',
		description: 'Колонка newsletter_template_items.locked',
	)]
	public int $locked = 0;
	#[OA\Property(
		property: 'sort_order',
		type: 'integer',
		description: 'Колонка newsletter_template_items.sort_order',
	)]
	public int $sort_order = 0;
	#[OA\Property(
		property: 'created_at',
		type: 'integer',
		description: 'Колонка newsletter_template_items.created_at',
	)]
	public int $created_at = 0;
	#[OA\Property(
		property: 'created_by',
		type: 'integer',
		description: 'Колонка newsletter_template_items.created_by',
	)]
	public int $created_by = 0;

	public function table(): string {
		return 'newsletter_template_items';
	}

	protected function columnList(): array {
		return [
			'id',
			'category_id',
			'title',
			'content',
			'locked',
			'sort_order',
			'created_at',
			'created_by',
		];
	}

	protected function defaultMap(): array {
		return [
			'category_id' => null,
			'title' => '',
			'content' => '',
			'locked' => 0,
			'sort_order' => 0,
			'created_at' => 0,
			'created_by' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
