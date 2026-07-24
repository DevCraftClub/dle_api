<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `question` (DLE install.php).
 */
#[OA\Schema(schema: 'Question')]
final class QuestionSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (question.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'question',
		type: 'string',
		description: 'Колонка question.question',
	)]
	public string $question = '';
	#[OA\Property(
		property: 'answer',
		type: 'string',
		description: 'Колонка question.answer',
	)]
	public string $answer = '';

	public function table(): string {
		return 'question';
	}

	protected function columnList(): array {
		return [
			'id',
			'question',
			'answer',
		];
	}

	protected function defaultMap(): array {
		return [
			'question' => '',
			'answer' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
