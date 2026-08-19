<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `conversations_messages`.
 */
#[OA\Schema(schema: 'ConversationsMessages')]
final class ConversationsMessagesSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (conversations_messages.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'conversation_id',
		type: 'integer',
		description: 'Колонка conversations_messages.conversation_id',
	)]
	public int $conversation_id = 0;
	#[OA\Property(
		property: 'sender_id',
		type: 'integer',
		description: 'Колонка conversations_messages.sender_id',
	)]
	public int $sender_id = 0;
	#[OA\Property(
		property: 'content',
		type: 'string',
		description: 'Колонка conversations_messages.content',
	)]
	public string $content = '';
	#[OA\Property(
		property: 'created_at',
		type: 'integer',
		description: 'Колонка conversations_messages.created_at',
	)]
	public int $created_at = 0;

	public function table(): string {
		return 'conversations_messages';
	}

	protected function columnList(): array {
		return [
			'id',
			'conversation_id',
			'sender_id',
			'content',
			'created_at',
		];
	}

	protected function defaultMap(): array {
		return [
			'conversation_id' => 0,
			'sender_id' => 0,
			'content' => '',
			'created_at' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
