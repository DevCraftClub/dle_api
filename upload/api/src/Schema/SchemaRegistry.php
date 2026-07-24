<?php

declare(strict_types=1);

namespace DleApi\Schema;

/**
 * Реестр Schema таблиц ядра DLE (автообнаружение *Schema.php).
 */
final class SchemaRegistry {
	/** @var array<string, class-string<TableSchemaInterface>>|null */
	private static ?array $map = null;

	/** @var array<string, TableSchemaInterface> */
	private static array $instances = [];

	/**
	 * @return array<string, class-string<TableSchemaInterface>>
	 */
	private static function map(): array {
		if(self::$map !== null) {
			return self::$map;
		}
		self::$map = [];
		$dir = __DIR__;
		foreach(scandir($dir) ?: [] as $file) {
			if(!str_ends_with($file, 'Schema.php')) {
				continue;
			}
			if(
				str_starts_with($file, 'Abstract')
				|| $file === 'TableSchemaInterface.php'
				|| $file === 'IntrospectedTableSchema.php'
			) {
				continue;
			}
			$class = __NAMESPACE__ . '\\' . substr($file, 0, -4);
			if(!class_exists($class) || !is_subclass_of($class, TableSchemaInterface::class)) {
				continue;
			}
			/** @var TableSchemaInterface $inst */
			$inst = new $class();
			self::$map[$inst->table()] = $class;
		}

		return self::$map;
	}

	/** @return list<string> */
	public static function tables(): array {
		return array_keys(self::map());
	}

	/** Логические имена для OpenAPI enum (динамически). */
	public static function tableNames(): array {
		return self::tables();
	}

	public static function get(string $table): TableSchemaInterface {
		$map = self::map();
		if(!isset($map[$table])) {
			throw new \InvalidArgumentException("Неизвестная таблица Schema: {$table}");
		}
		if(!isset(self::$instances[$table])) {
			$class = $map[$table];
			self::$instances[$table] = new $class();
		}

		return self::$instances[$table];
	}

	/**
	 * Новый экземпляр Schema для мутаций / HTTP (не shared singleton).
	 */
	public static function make(string $table): TableSchemaInterface {
		$map = self::map();
		if(!isset($map[$table])) {
			throw new \InvalidArgumentException("Неизвестная таблица Schema: {$table}");
		}
		$class = $map[$table];

		return new $class();
	}
}
