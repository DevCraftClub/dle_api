<?php

declare(strict_types=1);

namespace DleApi\Schema;

/**
 * Метаданные таблицы ядра DLE (без Cycle Entity).
 */
interface TableSchemaInterface {
	public function table(): string;

	/** @return list<string> */
	public function columns(): array;

	/** @return array<string, mixed> */
	public function defaults(): array;

	/**
	 * Фильтрация и поиск записей схемы по атрибутам.
	 *
	 * @param array<string, mixed>  $attrs
	 * @param list<string>          $selectedColumns список колонок; [] = все
	 * @param array<string, string> $order           колонка => ASC|DESC; [] = PK DESC
	 * @return list<static>
	 */
	public static function filter(array $attrs, array $selectedColumns = [], array $order = [], int $limit = 20, int $offset = 0): array;

	/** @return string|list<string> */
	public function primaryKey(): string|array;

	/**
	 * Виртуальные FK (RelationMap), т.к. в DLE нет MySQL FK.
	 *
	 * @return list<array{from: string, column: string, to: string, toColumn: string, kind: string}>
	 */
	public function relations(): array;

	/**
	 * Белый список атрибутов по колонкам схемы.
	 *
	 * @param array<string, mixed> $attrs
	 * @return array<string, mixed>
	 */
	public function showColumns(array $attrs): array;

	/**
	 * Создаёт экземпляр схемы из ассоциативного массива колонок.
	 *
	 * @param array<string, mixed> $data
	 */
	public static function fromArray(array $data): static;

	/** Вставляет запись (и вложенные children). */
	public function create(): static;

	/** Обновляет запись по PK или создаёт, если PK пуст. */
	public function save(): static;

	/** Удаляет запись по PK. */
	public function delete(): static;

	/** Задаёт значение колонки. */
	public function with(string $attrName, mixed $value): static;

	/** JSON-представление колонок. */
	public function asJson(): string;

	/**
	 * Ассоциативный массив колонок.
	 *
	 * @return array<string, mixed>
	 */
	public function asArray(): array;
}
