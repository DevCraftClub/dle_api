<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `subscribe`.
 */
#[OA\Schema(schema: 'Subscribe')]
final class SubscribeSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (subscribe.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (subscribe.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (subscribe.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'email',
		type: 'string',
		description: 'E-mail (subscribe.email)',
	)]
	public string $email = '';
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (subscribe.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'hash',
		type: 'string',
		description: 'Колонка subscribe.hash',
	)]
	public string $hash = '';

	public function table(): string {
		return 'subscribe';
	}

	protected function columnList(): array {
		return [
			'id',
			'user_id',
			'name',
			'email',
			'news_id',
			'hash',
		];
	}

	protected function defaultMap(): array {
		return [
			'user_id' => 0,
			'name' => '',
			'email' => '',
			'news_id' => 0,
			'hash' => '',
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
 * Схема таблицы `subscribe`.
 */
#[OA\Schema(schema: 'Subscribe')]
final class SubscribeSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (subscribe.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (subscribe.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (subscribe.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'email',
		type: 'string',
		description: 'E-mail (subscribe.email)',
	)]
	public string $email = '';
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (subscribe.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'hash',
		type: 'string',
		description: 'Колонка subscribe.hash',
	)]
	public string $hash = '';

	public function table(): string {
		return 'subscribe';
	}

	protected function columnList(): array {
		return [
			'id',
			'user_id',
			'name',
			'email',
			'news_id',
			'hash',
		];
	}

	protected function defaultMap(): array {
		return [
			'user_id' => 0,
			'name' => '',
			'email' => '',
			'news_id' => 0,
			'hash' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
