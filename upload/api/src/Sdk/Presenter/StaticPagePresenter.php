<?php

declare(strict_types=1);

namespace DleApi\Sdk\Presenter;

/**
 * Презентер статической страницы (static).
 *
 * Колонки StaticSchema: name, descr, template, metatitle (нет title/content).
 */
final class StaticPagePresenter extends AbstractTablePresenter {
	public function table(): string {
		return 'static';
	}

	public function withName(string $name): static {
		return $this->with('name', $name);
	}

	/**
	 * Заголовок → metatitle (в static нет колонки title).
	 */
	public function withTitle(string $title): static {
		return $this->with('metatitle', $title);
	}

	/**
	 * Контент страницы → template.
	 */
	public function withContent(string $content): static {
		return $this->with('template', $content);
	}
}
