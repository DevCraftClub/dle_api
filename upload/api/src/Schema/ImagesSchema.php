<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `images`.
 */
#[OA\Schema(schema: 'Images')]
final class ImagesSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (images.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'images',
		type: 'string',
		description: 'Колонка images.images',
	)]
	public string $images = '';
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (images.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'author',
		type: 'string',
		description: 'Колонка images.author',
	)]
	public string $author = '';
	#[OA\Property(
		property: 'date',
		type: 'string',
		description: 'Дата/время (images.date)',
	)]
	public string $date = '';

	public function table(): string {
		return 'images';
	}

	protected function columnList(): array {
		return [
			'id',
			'images',
			'news_id',
			'author',
			'date',
		];
	}

	protected function defaultMap(): array {
		return [
			'images' => '',
			'news_id' => 0,
			'author' => '',
			'date' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}

	public function withImages(string $images): static {
		return $this->with('images', $images);
	}

	public function withNewsId(int $newsId): static {
		return $this->with('news_id', $newsId);
	}

	public function withAuthor(string $author): static {
		return $this->with('author', $author);
	}

	public function withDate(string $date): static {
		return $this->with('date', $date);
	}
}
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `images`.
 */
#[OA\Schema(schema: 'Images')]
final class ImagesSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (images.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'images',
		type: 'string',
		description: 'Колонка images.images',
	)]
	public string $images = '';
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (images.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'author',
		type: 'string',
		description: 'Колонка images.author',
	)]
	public string $author = '';
	#[OA\Property(
		property: 'date',
		type: 'string',
		description: 'Дата/время (images.date)',
	)]
	public string $date = '';

	public function table(): string {
		return 'images';
	}

	protected function columnList(): array {
		return [
			'id',
			'images',
			'news_id',
			'author',
			'date',
		];
	}

	protected function defaultMap(): array {
		return [
			'images' => '',
			'news_id' => 0,
			'author' => '',
			'date' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}

	public function withImages(string $images): static {
		return $this->with('images', $images);
	}

	public function withNewsId(int $newsId): static {
		return $this->with('news_id', $newsId);
	}

	public function withAuthor(string $author): static {
		return $this->with('author', $author);
	}

	public function withDate(string $date): static {
		return $this->with('date', $date);
	}
}
>>>>>>> Current commit: Начало обновления до api v2
