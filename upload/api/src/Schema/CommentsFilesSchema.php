<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `comments_files`.
 */
#[OA\Schema(schema: 'CommentsFiles')]
final class CommentsFilesSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (comments_files.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'c_id',
		type: 'integer',
		description: 'ID комментария (comments_files.c_id)',
	)]
	public int $c_id = 0;
	#[OA\Property(
		property: 'author',
		type: 'string',
		description: 'Колонка comments_files.author',
	)]
	public string $author = '';
	#[OA\Property(
		property: 'date',
		type: 'string',
		description: 'Дата/время (comments_files.date)',
	)]
	public string $date = '';
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (comments_files.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'driver',
		type: 'integer',
		description: 'Колонка comments_files.driver',
	)]
	public int $driver = 0;

	public function table(): string {
		return 'comments_files';
	}

	protected function columnList(): array {
		return [
			'id',
			'c_id',
			'author',
			'date',
			'name',
			'driver',
		];
	}

	protected function defaultMap(): array {
		return [
			'c_id' => 0,
			'author' => '',
			'date' => '',
			'name' => '',
			'driver' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
