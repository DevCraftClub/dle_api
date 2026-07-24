<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `logs`.
 */
#[OA\Schema(schema: 'Logs')]
final class LogsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (logs.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (logs.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'member',
		type: 'string',
		description: 'Колонка logs.member',
	)]
	public string $member = '';
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (logs.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'rating',
		type: 'integer',
		description: 'Колонка logs.rating',
	)]
	public int $rating = 0;

	public function table(): string {
		return 'logs';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
			'member',
			'ip',
			'rating',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
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
 * Схема таблицы `logs` (DLE install.php).
 */
#[OA\Schema(schema: 'Logs')]
final class LogsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (logs.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (logs.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'member',
		type: 'string',
		description: 'Колонка logs.member',
	)]
	public string $member = '';
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (logs.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'rating',
		type: 'integer',
		description: 'Колонка logs.rating',
	)]
	public int $rating = 0;

	public function table(): string {
		return 'logs';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
			'member',
			'ip',
			'rating',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
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
