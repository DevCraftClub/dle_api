<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `conversations`.
 */
#[OA\Schema(schema: 'Conversations')]
final class ConversationsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (conversations.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'subject',
		type: 'string',
		description: 'Колонка conversations.subject',
	)]
	public string $subject = '';
	#[OA\Property(
		property: 'created_at',
		type: 'integer',
		description: 'Колонка conversations.created_at',
	)]
	public int $created_at = 0;
	#[OA\Property(
		property: 'updated_at',
		type: 'integer',
		description: 'Колонка conversations.updated_at',
	)]
	public int $updated_at = 0;
	#[OA\Property(
		property: 'sender_id',
		type: 'integer',
		description: 'Колонка conversations.sender_id',
	)]
	public int $sender_id = 0;
	#[OA\Property(
		property: 'recipient_id',
		type: 'integer',
		description: 'Колонка conversations.recipient_id',
	)]
	public int $recipient_id = 0;

	public function table(): string {
		return 'conversations';
	}

	protected function columnList(): array {
		return [
			'id',
			'subject',
			'created_at',
			'updated_at',
			'sender_id',
			'recipient_id',
		];
	}

	protected function defaultMap(): array {
		return [
			'subject' => '',
			'created_at' => 0,
			'updated_at' => 0,
			'sender_id' => 0,
			'recipient_id' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}

	public function withSenderId(int $senderId): static {
		return $this->with('sender_id', $senderId);
	}

	public function withRecipientId(int $recipientId): static {
		return $this->with('recipient_id', $recipientId);
	}

	public function withMessageEntity(ConversationsMessagesSchema $entity): static {
		return $this->attachChildEntity('conversations_messages', $entity);
	}

	public function withUsersEntity(ConversationUsersSchema $entity): static {
		return $this->attachChildEntity('conversation_users', $entity);
	}

	public function withReadsEntity(ConversationReadsSchema $entity): static {
		return $this->attachChildEntity('conversation_reads', $entity);
	}
}
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `conversations`.
 */
#[OA\Schema(schema: 'Conversations')]
final class ConversationsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (conversations.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'subject',
		type: 'string',
		description: 'Колонка conversations.subject',
	)]
	public string $subject = '';
	#[OA\Property(
		property: 'created_at',
		type: 'integer',
		description: 'Колонка conversations.created_at',
	)]
	public int $created_at = 0;
	#[OA\Property(
		property: 'updated_at',
		type: 'integer',
		description: 'Колонка conversations.updated_at',
	)]
	public int $updated_at = 0;
	#[OA\Property(
		property: 'sender_id',
		type: 'integer',
		description: 'Колонка conversations.sender_id',
	)]
	public int $sender_id = 0;
	#[OA\Property(
		property: 'recipient_id',
		type: 'integer',
		description: 'Колонка conversations.recipient_id',
	)]
	public int $recipient_id = 0;

	public function table(): string {
		return 'conversations';
	}

	protected function columnList(): array {
		return [
			'id',
			'subject',
			'created_at',
			'updated_at',
			'sender_id',
			'recipient_id',
		];
	}

	protected function defaultMap(): array {
		return [
			'subject' => '',
			'created_at' => 0,
			'updated_at' => 0,
			'sender_id' => 0,
			'recipient_id' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}

	public function withSenderId(int $senderId): static {
		return $this->with('sender_id', $senderId);
	}

	public function withRecipientId(int $recipientId): static {
		return $this->with('recipient_id', $recipientId);
	}

	public function withMessageEntity(ConversationsMessagesSchema $entity): static {
		return $this->attachChildEntity('conversations_messages', $entity);
	}

	public function withUsersEntity(ConversationUsersSchema $entity): static {
		return $this->attachChildEntity('conversation_users', $entity);
	}

	public function withReadsEntity(ConversationReadsSchema $entity): static {
		return $this->attachChildEntity('conversation_reads', $entity);
	}
}
>>>>>>> Current commit: Начало обновления до api v2
