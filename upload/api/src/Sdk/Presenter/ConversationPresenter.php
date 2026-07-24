<?php

declare(strict_types=1);

namespace DleApi\Sdk\Presenter;

/**
 * Презентер переписки (conversations).
 */
final class ConversationPresenter extends AbstractTablePresenter {
	public function table(): string {
		return 'conversations';
	}

	public function withSenderId(int $userId): static {
		return $this->with('sender_id', $userId);
	}

	public function withRecipientId(int $userId): static {
		return $this->with('recipient_id', $userId);
	}

	/**
	 * @param array<string, mixed>|list<array<string, mixed>> $message
	 */
	public function withMessage(array $message): static {
		return $this->withChild('conversations_messages', $message);
	}
}
