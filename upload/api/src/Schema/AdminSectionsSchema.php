<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `admin_sections`.
 */
#[OA\Schema(schema: 'AdminSections')]
final class AdminSectionsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (admin_sections.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (admin_sections.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'title',
		type: 'string',
		description: 'Заголовок (admin_sections.title)',
	)]
	public string $title = '';
	#[OA\Property(
		property: 'descr',
		type: 'string',
		description: 'Описание (admin_sections.descr)',
	)]
	public string $descr = '';
	#[OA\Property(
		property: 'icon',
		type: 'string',
		description: 'Колонка admin_sections.icon',
	)]
	public string $icon = '';
	#[OA\Property(
		property: 'allow_groups',
		type: 'string',
		description: 'CSV id или all (таблица admin_sections.allow_groups)',
	)]
	public string $allow_groups = '';

	public function table(): string {
		return 'admin_sections';
	}

	protected function columnList(): array {
		return [
			'id',
			'name',
			'title',
			'descr',
			'icon',
			'allow_groups',
		];
	}

	protected function defaultMap(): array {
		return [
			'name' => '',
			'title' => '',
			'descr' => '',
			'icon' => '',
			'allow_groups' => '',
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
 * Схема таблицы `admin_sections`.
 */
#[OA\Schema(schema: 'AdminSections')]
final class AdminSectionsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (admin_sections.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (admin_sections.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'title',
		type: 'string',
		description: 'Заголовок (admin_sections.title)',
	)]
	public string $title = '';
	#[OA\Property(
		property: 'descr',
		type: 'string',
		description: 'Описание (admin_sections.descr)',
	)]
	public string $descr = '';
	#[OA\Property(
		property: 'icon',
		type: 'string',
		description: 'Колонка admin_sections.icon',
	)]
	public string $icon = '';
	#[OA\Property(
		property: 'allow_groups',
		type: 'string',
		description: 'CSV id или all (таблица admin_sections.allow_groups)',
	)]
	public string $allow_groups = '';

	public function table(): string {
		return 'admin_sections';
	}

	protected function columnList(): array {
		return [
			'id',
			'name',
			'title',
			'descr',
			'icon',
			'allow_groups',
		];
	}

	protected function defaultMap(): array {
		return [
			'name' => '',
			'title' => '',
			'descr' => '',
			'icon' => '',
			'allow_groups' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
