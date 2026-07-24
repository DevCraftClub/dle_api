<?php

declare(strict_types=1);

namespace DleApi\Sdk\Presenter;

/**
 * Презентер пользователя (users).
 */
final class UserPresenter extends AbstractTablePresenter {
	public function table(): string {
		return 'users';
	}

	public function withName(string $name): static {
		return $this->with('name', $name);
	}

	public function withEmail(string $email): static {
		return $this->with('email', $email);
	}

	public function withPassword(string $password): static {
		return $this->with('password', $password);
	}

	public function withUsergroup(int|string $groupId): static {
		return $this->with('user_group', $groupId);
	}
}
