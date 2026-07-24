<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `notice`.
 */
#[OA\Schema(schema: 'Notice')]
final class NoticeSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (notice.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (notice.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'notice',
		type: 'string',
		description: 'Колонка notice.notice',
	)]
	public string $notice = '';

	public function table(): string {
		return 'notice';
	}

	protected function columnList(): array {
		return [
			'id',
			'user_id',
			'notice',
		];
	}

	protected function defaultMap(): array {
		return [
			'user_id' => 0,
			'notice' => '',
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
 * Схема таблицы `notice` (DLE install.php).
 */
#[OA\Schema(schema: 'Notice')]
final class NoticeSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (notice.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (notice.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'notice',
		type: 'string',
		description: 'Колонка notice.notice',
	)]
	public string $notice = '';

	public function table(): string {
		return 'notice';
	}

	protected function columnList(): array {
		return [
			'id',
			'user_id',
			'notice',
		];
	}

	protected function defaultMap(): array {
		return [
			'user_id' => 0,
			'notice' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
