<?php

declare(strict_types=1);

namespace DleApi\Xfield;

/**
 * Ошибка валидации определения / значения доп. поля.
 */
final class XfieldValidationException extends \RuntimeException {
	/** @param array<string, string> $fields имя свойства → сообщение */
	public function __construct(
		string $message,
		private array $fields = [],
	) {
		parent::__construct($message);
	}

	/** @return array<string, string> */
	public function fields(): array {
		return $this->fields;
	}

	/** @return array{fields: array<string, string>} */
	public function details(): array {
		return ['fields' => $this->fields];
	}
}
