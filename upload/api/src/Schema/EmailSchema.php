<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `email`.
 */
#[OA\Schema(schema: 'Email')]
final class EmailSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (email.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (email.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'template',
		type: 'string',
		description: 'Колонка email.template',
	)]
	public string $template = '';
	#[OA\Property(
		property: 'use_html',
		type: 'integer',
		description: 'Колонка email.use_html',
	)]
	public int $use_html = 0;

	public function table(): string {
		return 'email';
	}

	protected function columnList(): array {
		return [
			'id',
			'name',
			'template',
			'use_html',
		];
	}

	protected function defaultMap(): array {
		return [
			'name' => '',
			'template' => '',
			'use_html' => 0,
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
 * Схема таблицы `email`.
 */
#[OA\Schema(schema: 'Email')]
final class EmailSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (email.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (email.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'template',
		type: 'string',
		description: 'Колонка email.template',
	)]
	public string $template = '';
	#[OA\Property(
		property: 'use_html',
		type: 'integer',
		description: 'Колонка email.use_html',
	)]
	public int $use_html = 0;

	public function table(): string {
		return 'email';
	}

	protected function columnList(): array {
		return [
			'id',
			'name',
			'template',
			'use_html',
		];
	}

	protected function defaultMap(): array {
		return [
			'name' => '',
			'template' => '',
			'use_html' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
