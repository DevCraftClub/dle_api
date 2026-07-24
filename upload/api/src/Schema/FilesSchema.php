<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `files`.
 */
#[OA\Schema(schema: 'Files')]
final class FilesSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (files.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (files.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (files.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'onserver',
		type: 'string',
		description: 'Колонка files.onserver',
	)]
	public string $onserver = '';
	#[OA\Property(
		property: 'author',
		type: 'string',
		description: 'Колонка files.author',
	)]
	public string $author = '';
	#[OA\Property(
		property: 'date',
		type: 'string',
		description: 'Дата/время (files.date)',
	)]
	public string $date = '';
	#[OA\Property(
		property: 'dcount',
		type: 'integer',
		description: 'Колонка files.dcount',
	)]
	public int $dcount = 0;
	#[OA\Property(
		property: 'size',
		type: 'integer',
		description: 'Колонка files.size',
	)]
	public int $size = 0;
	#[OA\Property(
		property: 'checksum',
		type: 'string',
		description: 'Колонка files.checksum',
	)]
	public string $checksum = '';
	#[OA\Property(
		property: 'driver',
		type: 'integer',
		description: 'Колонка files.driver',
	)]
	public int $driver = 0;
	#[OA\Property(
		property: 'is_public',
		type: 'integer',
		description: 'Колонка files.is_public',
	)]
	public int $is_public = 0;

	public function table(): string {
		return 'files';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
			'name',
			'onserver',
			'author',
			'date',
			'dcount',
			'size',
			'checksum',
			'driver',
			'is_public',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
			'name' => '',
			'onserver' => '',
			'author' => '',
			'date' => '',
			'dcount' => 0,
			'size' => 0,
			'checksum' => '',
			'driver' => 0,
			'is_public' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}

	public function withName(string $name): static {
		return $this->with('name', $name);
	}

	public function withOnserver(string $onserver): static {
		return $this->with('onserver', $onserver);
	}

	public function withNewsId(int $newsId): static {
		return $this->with('news_id', $newsId);
	}

	public function withAuthor(string $author): static {
		return $this->with('author', $author);
	}

	public function withDriver(int $driver): static {
		return $this->with('driver', $driver);
	}
}
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `files` (DLE install.php).
 */
#[OA\Schema(schema: 'Files')]
final class FilesSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (files.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (files.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (files.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'onserver',
		type: 'string',
		description: 'Колонка files.onserver',
	)]
	public string $onserver = '';
	#[OA\Property(
		property: 'author',
		type: 'string',
		description: 'Колонка files.author',
	)]
	public string $author = '';
	#[OA\Property(
		property: 'date',
		type: 'string',
		description: 'Дата/время (files.date)',
	)]
	public string $date = '';
	#[OA\Property(
		property: 'dcount',
		type: 'integer',
		description: 'Колонка files.dcount',
	)]
	public int $dcount = 0;
	#[OA\Property(
		property: 'size',
		type: 'integer',
		description: 'Колонка files.size',
	)]
	public int $size = 0;
	#[OA\Property(
		property: 'checksum',
		type: 'string',
		description: 'Колонка files.checksum',
	)]
	public string $checksum = '';
	#[OA\Property(
		property: 'driver',
		type: 'integer',
		description: 'Колонка files.driver',
	)]
	public int $driver = 0;
	#[OA\Property(
		property: 'is_public',
		type: 'integer',
		description: 'Колонка files.is_public',
	)]
	public int $is_public = 0;

	public function table(): string {
		return 'files';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
			'name',
			'onserver',
			'author',
			'date',
			'dcount',
			'size',
			'checksum',
			'driver',
			'is_public',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
			'name' => '',
			'onserver' => '',
			'author' => '',
			'date' => '',
			'dcount' => 0,
			'size' => 0,
			'checksum' => '',
			'driver' => 0,
			'is_public' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}

	public function withName(string $name): static {
		return $this->with('name', $name);
	}

	public function withOnserver(string $onserver): static {
		return $this->with('onserver', $onserver);
	}

	public function withNewsId(int $newsId): static {
		return $this->with('news_id', $newsId);
	}

	public function withAuthor(string $author): static {
		return $this->with('author', $author);
	}

	public function withDriver(int $driver): static {
		return $this->with('driver', $driver);
	}
}
>>>>>>> Current commit: Начало обновления до api v2
