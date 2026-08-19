<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `links`.
 */
#[OA\Schema(schema: 'Links')]
final class LinksSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (links.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'word',
		type: 'string',
		description: 'Колонка links.word',
	)]
	public string $word = '';
	#[OA\Property(
		property: 'link',
		type: 'string',
		description: 'Колонка links.link',
	)]
	public string $link = '';
	#[OA\Property(
		property: 'only_one',
		type: 'integer',
		description: 'Колонка links.only_one',
	)]
	public int $only_one = 0;
	#[OA\Property(
		property: 'replacearea',
		type: 'integer',
		description: 'Колонка links.replacearea',
	)]
	public int $replacearea = 1;
	#[OA\Property(
		property: 'rcount',
		type: 'integer',
		description: 'Колонка links.rcount',
	)]
	public int $rcount = 0;
	#[OA\Property(
		property: 'targetblank',
		type: 'integer',
		description: 'Колонка links.targetblank',
	)]
	public int $targetblank = 0;
	#[OA\Property(
		property: 'title',
		type: 'string',
		description: 'Заголовок (links.title)',
	)]
	public string $title = '';
	#[OA\Property(
		property: 'enabled',
		type: 'integer',
		description: 'Колонка links.enabled',
	)]
	public int $enabled = 1;

	public function table(): string {
		return 'links';
	}

	protected function columnList(): array {
		return [
			'id',
			'word',
			'link',
			'only_one',
			'replacearea',
			'rcount',
			'targetblank',
			'title',
			'enabled',
		];
	}

	protected function defaultMap(): array {
		return [
			'word' => '',
			'link' => '',
			'only_one' => 0,
			'replacearea' => 1,
			'rcount' => 0,
			'targetblank' => 0,
			'title' => '',
			'enabled' => 1,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
