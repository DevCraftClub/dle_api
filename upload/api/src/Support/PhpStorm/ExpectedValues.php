<?php

declare(strict_types=1);

namespace JetBrains\PhpStorm;

use Attribute;

/**
 * Polyfill PhpStorm ExpectedValues (если jetbrains/phpstorm-attributes не установлен).
 *
 * @see https://github.com/JetBrains/phpstorm-stubs/blob/master/meta/attributes/ExpectedValues.php
 */
#[Attribute(Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD | Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
class ExpectedValues {
	/**
	 * @param list<mixed> $values
	 * @param list<mixed> $flags
	 */
	public function __construct(
		array $values = [],
		array $flags = [],
		?string $valuesFromClass = null,
		?string $flagsFromClass = null,
	) {
	}
}
