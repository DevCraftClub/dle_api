<?php

declare(strict_types=1);

namespace DleApi\Sdk;

/**
 * Ошибка SDK/Schema с JSON-телом для вызывающей стороны.
 */
final class SdkException extends \RuntimeException {
	public function __construct(
		private readonly string $errorCode,
		string $message,
		private readonly int $httpStatus = 400,
	) {
		parent::__construct($message, $httpStatus);
	}

	public function errorCode(): string {
		return $this->errorCode;
	}

	public function httpStatus(): int {
		return $this->httpStatus;
	}

	/** @return array{error: string, message: string} */
	public function toArray(): array {
		return [
			'error'   => $this->errorCode,
			'message' => $this->getMessage(),
		];
	}

	public function asJson(): string {
		return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
	}

	public static function missingPrimaryKey(string $hint = ''): self {
		$msg = __('Нельзя выполнить операцию: не задан первичный ключ.');
		if($hint !== '') {
			$msg .= ' ' . $hint;
		}

		return new self('missing_primary_key', $msg, 422);
	}

	public static function unknownColumn(string $table, string $column): self {
		return new self(
			'unknown_column',
			__('Неизвестная колонка «{column}» для таблицы {table}', [
				'{column}' => $column,
				'{table}'  => $table,
			]),
			422,
		);
	}

	public static function forbiddenScope(string $table, string $action): self {
		return new self(
			'forbidden_scope',
			__('Нет права «{action}» на таблицу «{table}»', [
				'{action}' => $action,
				'{table}'  => $table,
			]),
			403,
		);
	}
}
