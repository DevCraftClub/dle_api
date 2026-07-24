<?php

declare(strict_types=1);

namespace DleApi\Schema;

/**
 * Magic withTitle/withAllowComm → with('title'|… ) для Schema и TableBuilder.
 */
trait WithColumnMagicTrait {
	/**
	 * @param list<mixed> $arguments
	 */
	public function __call(string $name, array $arguments): static {
		$col = self::withMethodToColumn($name);
		if($arguments === []) {
			throw new \InvalidArgumentException(__('Метод требует значение') . ": {$name}");
		}
		/** @var static $self */
		$self = $this->with($col, $arguments[0]);

		return $self;
	}

	/**
	 * withTitle → title; withAllowComm → allow_comm.
	 */
	public static function withMethodToColumn(string $method): string {
		if(!str_starts_with($method, 'with') || $method === 'with' || strlen($method) < 5) {
			throw new \BadMethodCallException(__('Неизвестный метод') . ": {$method}");
		}

		return self::camelToSnake(substr($method, 4));
	}

	public static function camelToSnake(string $name): string {
		$s = preg_replace('/([a-z])([A-Z])/', '$1_$2', $name) ?? $name;

		return strtolower($s);
	}
}
