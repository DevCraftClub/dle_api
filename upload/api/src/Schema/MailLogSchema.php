<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `mail_log`.
 */
#[OA\Schema(schema: 'MailLog')]
final class MailLogSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (mail_log.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (mail_log.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'mail',
		type: 'string',
		description: 'Колонка mail_log.mail',
	)]
	public string $mail = '';
	#[OA\Property(
		property: 'hash',
		type: 'string',
		description: 'Колонка mail_log.hash',
	)]
	public string $hash = '';

	public function table(): string {
		return 'mail_log';
	}

	protected function columnList(): array {
		return [
			'id',
			'user_id',
			'mail',
			'hash',
		];
	}

	protected function defaultMap(): array {
		return [
			'user_id' => 0,
			'mail' => '',
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
 * Схема таблицы `mail_log` (DLE install.php).
 */
#[OA\Schema(schema: 'MailLog')]
final class MailLogSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (mail_log.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (mail_log.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'mail',
		type: 'string',
		description: 'Колонка mail_log.mail',
	)]
	public string $mail = '';
	#[OA\Property(
		property: 'hash',
		type: 'string',
		description: 'Колонка mail_log.hash',
	)]
	public string $hash = '';

	public function table(): string {
		return 'mail_log';
	}

	protected function columnList(): array {
		return [
			'id',
			'user_id',
			'mail',
			'hash',
		];
	}

	protected function defaultMap(): array {
		return [
			'user_id' => 0,
			'mail' => '',
			'hash' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
