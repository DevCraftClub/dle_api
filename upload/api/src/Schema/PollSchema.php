<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `poll` (DLE install.php).
 */
#[OA\Schema(schema: 'Poll')]
final class PollSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (poll.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (poll.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'title',
		type: 'string',
		description: 'Заголовок (poll.title)',
	)]
	public string $title = '';
	#[OA\Property(
		property: 'frage',
		type: 'string',
		description: 'Колонка poll.frage',
	)]
	public string $frage = '';
	#[OA\Property(
		property: 'body',
		type: 'string',
		description: 'Колонка poll.body',
	)]
	public string $body = '';
	#[OA\Property(
		property: 'votes',
		type: 'integer',
		description: 'Колонка poll.votes',
	)]
	public int $votes = 0;
	#[OA\Property(
		property: 'multiple',
		type: 'integer',
		description: 'Колонка poll.multiple',
	)]
	public int $multiple = 0;
	#[OA\Property(
		property: 'answer',
		type: 'string',
		description: 'Колонка poll.answer',
	)]
	public string $answer = '';
	#[OA\Property(
		property: 'closed',
		type: 'integer',
		description: 'Колонка poll.closed',
	)]
	public int $closed = 0;
	#[OA\Property(
		property: 'date_closed',
		type: 'string',
		description: 'Колонка poll.date_closed',
	)]
	public string $date_closed = '';

	public function table(): string {
		return 'poll';
	}

	protected function columnList(): array {
		return [
			'id',
			'news_id',
			'title',
			'frage',
			'body',
			'votes',
			'multiple',
			'answer',
			'closed',
			'date_closed',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
			'title' => '',
			'frage' => '',
			'body' => '',
			'votes' => 0,
			'multiple' => 0,
			'answer' => '',
			'closed' => 0,
			'date_closed' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
