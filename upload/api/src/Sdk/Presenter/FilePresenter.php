<?php

declare(strict_types=1);

namespace DleApi\Sdk\Presenter;

/**
 * Презентер файла вложений (files).
 */
final class FilePresenter extends AbstractTablePresenter {
	public function table(): string {
		return 'files';
	}

	public function withNewsId(int $newsId): static {
		return $this->with('news_id', $newsId);
	}

	public function withName(string $name): static {
		return $this->with('name', $name);
	}

	public function withOnserver(string $onserver): static {
		return $this->with('onserver', $onserver);
	}
}
