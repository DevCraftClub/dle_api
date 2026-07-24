<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `newsletter_template_categories`.
 */
#[OA\Schema(schema: 'NewsletterTemplateCategories')]
final class NewsletterTemplateCategoriesSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (newsletter_template_categories.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'title',
		type: 'string',
		description: 'Заголовок (newsletter_template_categories.title)',
	)]
	public string $title = '';
	#[OA\Property(
		property: 'locked',
		type: 'integer',
		description: 'Колонка newsletter_template_categories.locked',
	)]
	public int $locked = 0;
	#[OA\Property(
		property: 'sort_order',
		type: 'integer',
		description: 'Колонка newsletter_template_categories.sort_order',
	)]
	public int $sort_order = 0;
	#[OA\Property(
		property: 'created_at',
		type: 'integer',
		description: 'Колонка newsletter_template_categories.created_at',
	)]
	public int $created_at = 0;
	#[OA\Property(
		property: 'created_by',
		type: 'integer',
		description: 'Колонка newsletter_template_categories.created_by',
	)]
	public int $created_by = 0;

	public function table(): string {
		return 'newsletter_template_categories';
	}

	protected function columnList(): array {
		return [
			'id',
			'title',
			'locked',
			'sort_order',
			'created_at',
			'created_by',
		];
	}

	protected function defaultMap(): array {
		return [
			'title' => '',
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
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `newsletter_template_categories` (DLE install.php).
 */
#[OA\Schema(schema: 'NewsletterTemplateCategories')]
final class NewsletterTemplateCategoriesSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (newsletter_template_categories.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'title',
		type: 'string',
		description: 'Заголовок (newsletter_template_categories.title)',
	)]
	public string $title = '';
	#[OA\Property(
		property: 'locked',
		type: 'integer',
		description: 'Колонка newsletter_template_categories.locked',
	)]
	public int $locked = 0;
	#[OA\Property(
		property: 'sort_order',
		type: 'integer',
		description: 'Колонка newsletter_template_categories.sort_order',
	)]
	public int $sort_order = 0;
	#[OA\Property(
		property: 'created_at',
		type: 'integer',
		description: 'Колонка newsletter_template_categories.created_at',
	)]
	public int $created_at = 0;
	#[OA\Property(
		property: 'created_by',
		type: 'integer',
		description: 'Колонка newsletter_template_categories.created_by',
	)]
	public int $created_by = 0;

	public function table(): string {
		return 'newsletter_template_categories';
	}

	protected function columnList(): array {
		return [
			'id',
			'title',
			'locked',
			'sort_order',
			'created_at',
			'created_by',
		];
	}

	protected function defaultMap(): array {
		return [
			'title' => '',
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
>>>>>>> Current commit: Начало обновления до api v2
