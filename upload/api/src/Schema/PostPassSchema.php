<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `post_pass`.
 */
#[OA\Schema(schema: 'PostPass')]
final class PostPassSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (post_pass.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (post_pass.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'password',
		type: 'string',
		description: 'Хеш пароля (post_pass.password)',
	)]
	public string $password = '';

	public function table(): string {
		return 'post_pass';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
			'password',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
			'password' => '',
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
 * Схема таблицы `post_pass` (DLE install.php).
 */
#[OA\Schema(schema: 'PostPass')]
final class PostPassSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (post_pass.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (post_pass.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'password',
		type: 'string',
		description: 'Хеш пароля (post_pass.password)',
	)]
	public string $password = '';

	public function table(): string {
		return 'post_pass';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
			'password',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
			'password' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
