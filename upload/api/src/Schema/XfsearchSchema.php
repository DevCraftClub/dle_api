<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `xfsearch`.
 */
#[OA\Schema(schema: 'Xfsearch')]
final class XfsearchSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (xfsearch.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (xfsearch.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'tagname',
		type: 'string',
		description: 'Колонка xfsearch.tagname',
	)]
	public string $tagname = '';
	#[OA\Property(
		property: 'tagvalue',
		type: 'string',
		description: 'Колонка xfsearch.tagvalue',
	)]
	public string $tagvalue = '';

	public function table(): string {
		return 'xfsearch';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
			'tagname',
			'tagvalue',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
			'tagname' => '',
			'tagvalue' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
