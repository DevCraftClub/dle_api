<?php

declare(strict_types=1);

namespace DleApi\Sdk\Presenter;

/**
 * Базовый презентер конфигурации (xfields и т.п.).
 */
abstract class AbstractConfigPresenter extends AbstractPresenter {
	/**
	 * Сохраняет накопленные изменения.
	 */
	abstract public function save(): static;

	/**
	 * @param array<string, mixed> $attrs
	 */
	public function withAttributes(array $attrs): static {
		throw new \BadMethodCallException('withAttributes недоступен для конфиг-презентера');
	}

	public function with(string $name, mixed $value): static {
		throw new \BadMethodCallException("with({$name}) недоступен для конфиг-презентера");
	}
}
