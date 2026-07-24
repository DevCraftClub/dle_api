<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `conversation_users`.
 */
#[OA\Schema(schema: 'ConversationUsers')]
final class ConversationUsersSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (conversation_users.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'conversation_id',
		type: 'integer',
		description: 'Колонка conversation_users.conversation_id',
	)]
	public int $conversation_id = 0;

	public function table(): string {
		return 'conversation_users';
	}

	protected function columnList(): array {
		return [
			'user_id',
			'conversation_id',
		];
	}

	protected function defaultMap(): array {
		return [

		];
	}

	public function primaryKey(): string|array {
		return ['user_id', 'conversation_id'];
	}
}
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `conversation_users` (DLE install.php).
 */
#[OA\Schema(schema: 'ConversationUsers')]
final class ConversationUsersSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (conversation_users.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'conversation_id',
		type: 'integer',
		description: 'Колонка conversation_users.conversation_id',
	)]
	public int $conversation_id = 0;

	public function table(): string {
		return 'conversation_users';
	}

	protected function columnList(): array {
		return [
			'user_id',
			'conversation_id',
		];
	}

	protected function defaultMap(): array {
		return [

		];
	}

	public function primaryKey(): string|array {
		return ['user_id', 'conversation_id'];
	}
}
>>>>>>> Current commit: Начало обновления до api v2
