<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `twofactor`.
 */
#[OA\Schema(schema: 'Twofactor')]
final class TwofactorSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (twofactor.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (twofactor.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'pin',
		type: 'string',
		description: 'Колонка twofactor.pin',
	)]
	public string $pin = '';
	#[OA\Property(
		property: 'attempt',
		type: 'integer',
		description: 'Колонка twofactor.attempt',
	)]
	public int $attempt = 0;
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (twofactor.date)',
	)]
	public int $date = 0;

	public function table(): string {
		return 'twofactor';
	}

	protected function columnList(): array {
		return [
			'id',
			'user_id',
			'pin',
			'attempt',
			'date',
		];
	}

	protected function defaultMap(): array {
		return [
			'user_id' => 0,
			'pin' => '',
			'attempt' => 0,
			'date' => 0,
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
 * Схема таблицы `twofactor` (DLE install.php).
 */
#[OA\Schema(schema: 'Twofactor')]
final class TwofactorSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (twofactor.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (twofactor.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'pin',
		type: 'string',
		description: 'Колонка twofactor.pin',
	)]
	public string $pin = '';
	#[OA\Property(
		property: 'attempt',
		type: 'integer',
		description: 'Колонка twofactor.attempt',
	)]
	public int $attempt = 0;
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (twofactor.date)',
	)]
	public int $date = 0;

	public function table(): string {
		return 'twofactor';
	}

	protected function columnList(): array {
		return [
			'id',
			'user_id',
			'pin',
			'attempt',
			'date',
		];
	}

	protected function defaultMap(): array {
		return [
			'user_id' => 0,
			'pin' => '',
			'attempt' => 0,
			'date' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
