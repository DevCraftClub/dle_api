<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `redirects`.
 */
#[OA\Schema(schema: 'Redirects')]
final class RedirectsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (redirects.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'from',
		type: 'string',
		description: 'Колонка redirects.from',
	)]
	public string $from = '';
	#[OA\Property(
		property: 'to',
		type: 'string',
		description: 'Колонка redirects.to',
	)]
	public string $to = '';
	#[OA\Property(
		property: 'enabled',
		type: 'integer',
		description: 'Колонка redirects.enabled',
	)]
	public int $enabled = 1;

	public function table(): string {
		return 'redirects';
	}

	protected function columnList(): array {
		return [
			'id',
			'from',
			'to',
			'enabled',
		];
	}

	protected function defaultMap(): array {
		return [
			'from' => '',
			'to' => '',
			'enabled' => 1,
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
 * Схема таблицы `redirects`.
 */
#[OA\Schema(schema: 'Redirects')]
final class RedirectsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (redirects.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'from',
		type: 'string',
		description: 'Колонка redirects.from',
	)]
	public string $from = '';
	#[OA\Property(
		property: 'to',
		type: 'string',
		description: 'Колонка redirects.to',
	)]
	public string $to = '';
	#[OA\Property(
		property: 'enabled',
		type: 'integer',
		description: 'Колонка redirects.enabled',
	)]
	public int $enabled = 1;

	public function table(): string {
		return 'redirects';
	}

	protected function columnList(): array {
		return [
			'id',
			'from',
			'to',
			'enabled',
		];
	}

	protected function defaultMap(): array {
		return [
			'from' => '',
			'to' => '',
			'enabled' => 1,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
