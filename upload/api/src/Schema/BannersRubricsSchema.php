<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `banners_rubrics`.
 */
#[OA\Schema(schema: 'BannersRubrics')]
final class BannersRubricsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (banners_rubrics.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'parentid',
		type: 'integer',
		description: 'Колонка banners_rubrics.parentid',
	)]
	public int $parentid = 0;
	#[OA\Property(
		property: 'title',
		type: 'string',
		description: 'Заголовок (banners_rubrics.title)',
	)]
	public string $title = '';
	#[OA\Property(
		property: 'description',
		type: 'string',
		description: 'Колонка banners_rubrics.description',
	)]
	public string $description = '';

	public function table(): string {
		return 'banners_rubrics';
	}

	protected function columnList(): array {
		return [
			'id',
			'parentid',
			'title',
			'description',
		];
	}

	protected function defaultMap(): array {
		return [
			'parentid' => 0,
			'title' => '',
			'description' => '',
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
 * Схема таблицы `banners_rubrics` (DLE install.php).
 */
#[OA\Schema(schema: 'BannersRubrics')]
final class BannersRubricsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (banners_rubrics.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'parentid',
		type: 'integer',
		description: 'Колонка banners_rubrics.parentid',
	)]
	public int $parentid = 0;
	#[OA\Property(
		property: 'title',
		type: 'string',
		description: 'Заголовок (banners_rubrics.title)',
	)]
	public string $title = '';
	#[OA\Property(
		property: 'description',
		type: 'string',
		description: 'Колонка banners_rubrics.description',
	)]
	public string $description = '';

	public function table(): string {
		return 'banners_rubrics';
	}

	protected function columnList(): array {
		return [
			'id',
			'parentid',
			'title',
			'description',
		];
	}

	protected function defaultMap(): array {
		return [
			'parentid' => 0,
			'title' => '',
			'description' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
