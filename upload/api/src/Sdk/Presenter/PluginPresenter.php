<?php

declare(strict_types=1);

namespace DleApi\Sdk\Presenter;

/**
 * Презентер плагина (plugins).
 */
final class PluginPresenter extends AbstractTablePresenter {
	public function table(): string {
		return 'plugins';
	}

	public function withName(string $name): static {
		return $this->with('name', $name);
	}

	public function withDescription(string $description): static {
		return $this->with('description', $description);
	}

	public function withVersion(string $version): static {
		return $this->with('version', $version);
	}

	/** @param mixed $files TableBuilder|array|list → plugins_files */
	public function withFiles(mixed $files): static {
		return $this->withChild('plugins_files', $files);
	}
}
