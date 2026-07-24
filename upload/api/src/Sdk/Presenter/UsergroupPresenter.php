<?php

declare(strict_types=1);

namespace DleApi\Sdk\Presenter;

/**
 * Презентер группы пользователей (usergroups).
 */
final class UsergroupPresenter extends AbstractTablePresenter {
	public function table(): string {
		return 'usergroups';
	}

	public function withName(string $name): static {
		return $this->with('group_name', $name);
	}
}
