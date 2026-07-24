<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `users_delete` (DLE install.php).
 */
#[OA\Schema(schema: 'UsersDelete')]
final class UsersDeleteSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (users_delete.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (users_delete.user_id)',
	)]
	public int $user_id = 0;

	public function table(): string {
		return 'users_delete';
	}

	protected function columnList(): array {
		return [
			'id',
			'user_id',
		];
	}

	protected function defaultMap(): array {
		return [
			'user_id' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
