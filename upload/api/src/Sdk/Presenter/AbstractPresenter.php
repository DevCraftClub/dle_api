<?php

declare(strict_types=1);

namespace DleApi\Sdk\Presenter;

/**
 * Базовый презентер SDK.
 */
abstract class AbstractPresenter {
	protected ?int $createdId = null;

	abstract public function model(): object;

	/**
	 * ID созданной сущности.
	 *
	 * @throws \RuntimeException
	 */
	public function id(): int {
		if($this->createdId === null) {
			throw new \RuntimeException('Сущность ещё не создана: вызовите create()/save()');
		}

		return $this->createdId;
	}

	/**
	 * @param array<string, mixed> $attrs
	 */
	abstract public function withAttributes(array $attrs): static;

	/**
	 * Колонка или nested-связь.
	 */
	abstract public function with(string $name, mixed $value): static;

	/**
	 * Magic: withTitle('x') → with('title', 'x').
	 *
	 * @param list<mixed> $arguments
	 */
	public function __call(string $name, array $arguments): static {
		if(!str_starts_with($name, 'with') || $name === 'with' || strlen($name) < 5) {
			throw new \BadMethodCallException("Неизвестный метод {$name}");
		}
		if($arguments === []) {
			throw new \InvalidArgumentException("{$name} требует значение");
		}
		$col = strtolower((string) preg_replace('/([a-z])([A-Z])/', '$1_$2', substr($name, 4)));

		return $this->with($col, $arguments[0]);
	}
}
