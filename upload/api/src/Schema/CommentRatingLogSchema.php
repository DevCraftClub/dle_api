<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `comment_rating_log`.
 */
#[OA\Schema(schema: 'CommentRatingLog')]
final class CommentRatingLogSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (comment_rating_log.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'c_id',
		type: 'integer',
		description: 'ID комментария (comment_rating_log.c_id)',
	)]
	public int $c_id = 0;
	#[OA\Property(
		property: 'member',
		type: 'string',
		description: 'Колонка comment_rating_log.member',
	)]
	public string $member = '';
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (comment_rating_log.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'rating',
		type: 'integer',
		description: 'Колонка comment_rating_log.rating',
	)]
	public int $rating = 0;

	public function table(): string {
		return 'comment_rating_log';
	}

	protected function columnList(): array {
		return [
			'id',
			'c_id',
			'member',
			'ip',
			'rating',
		];
	}

	protected function defaultMap(): array {
		return [
			'c_id' => 0,
			'member' => '',
			'ip' => '',
			'rating' => 0,
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
 * Схема таблицы `comment_rating_log` (DLE install.php).
 */
#[OA\Schema(schema: 'CommentRatingLog')]
final class CommentRatingLogSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (comment_rating_log.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'c_id',
		type: 'integer',
		description: 'ID комментария (comment_rating_log.c_id)',
	)]
	public int $c_id = 0;
	#[OA\Property(
		property: 'member',
		type: 'string',
		description: 'Колонка comment_rating_log.member',
	)]
	public string $member = '';
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (comment_rating_log.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'rating',
		type: 'integer',
		description: 'Колонка comment_rating_log.rating',
	)]
	public int $rating = 0;

	public function table(): string {
		return 'comment_rating_log';
	}

	protected function columnList(): array {
		return [
			'id',
			'c_id',
			'member',
			'ip',
			'rating',
		];
	}

	protected function defaultMap(): array {
		return [
			'c_id' => 0,
			'member' => '',
			'ip' => '',
			'rating' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
