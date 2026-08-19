<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `static_files`.
 */
#[OA\Schema(schema: 'StaticFiles')]
final class StaticFilesSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (static_files.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'static_id',
		type: 'integer',
		description: 'Колонка static_files.static_id',
	)]
	public int $static_id = 0;
	#[OA\Property(
		property: 'author',
		type: 'string',
		description: 'Колонка static_files.author',
	)]
	public string $author = '';
	#[OA\Property(
		property: 'date',
		type: 'string',
		description: 'Дата/время (static_files.date)',
	)]
	public string $date = '';
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (static_files.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'onserver',
		type: 'string',
		description: 'Колонка static_files.onserver',
	)]
	public string $onserver = '';
	#[OA\Property(
		property: 'dcount',
		type: 'integer',
		description: 'Колонка static_files.dcount',
	)]
	public int $dcount = 0;
	#[OA\Property(
		property: 'size',
		type: 'integer',
		description: 'Колонка static_files.size',
	)]
	public int $size = 0;
	#[OA\Property(
		property: 'checksum',
		type: 'string',
		description: 'Колонка static_files.checksum',
	)]
	public string $checksum = '';
	#[OA\Property(
		property: 'driver',
		type: 'integer',
		description: 'Колонка static_files.driver',
	)]
	public int $driver = 0;
	#[OA\Property(
		property: 'is_public',
		type: 'integer',
		description: 'Колонка static_files.is_public',
	)]
	public int $is_public = 0;

	public function table(): string {
		return 'static_files';
	}

	protected function columnList(): array {
		return [
			'id',
			'static_id',
			'author',
			'date',
			'name',
			'onserver',
			'dcount',
			'size',
			'checksum',
			'driver',
			'is_public',
		];
	}

	protected function defaultMap(): array {
		return [
			'static_id' => 0,
			'author' => '',
			'date' => '',
			'name' => '',
			'onserver' => '',
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
}
