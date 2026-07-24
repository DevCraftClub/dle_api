<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `views`.
 */
#[OA\Schema(schema: 'Views')]
final class ViewsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (views.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (views.news_id)',
	)]
	public int $news_id = 0;

	public function table(): string {
		return 'views';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
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
 * Схема таблицы `views` (DLE install.php).
 */
#[OA\Schema(schema: 'Views')]
final class ViewsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (views.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (views.news_id)',
	)]
	public int $news_id = 0;

	public function table(): string {
		return 'views';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
