<?php

declare(strict_types=1);

namespace DleApi\Schema;

/**
 * Runtime-схема из SHOW COLUMNS (таблицы без *Schema.php).
 * Только для HTTP-resolver; SchemaRegistry::get её не отдаёт.
 */
final class IntrospectedTableSchema extends AbstractTableSchema {
	/** @var array<string, self> */
	private static array $cache = [];

	private const DENY = [
		'api_keys',
		'api_scope',
		'api_oauth_clients',
		'api_oauth_access_tokens',
		'api_oauth_refresh_tokens',
		'api_oauth_auth_codes',
		'oauth_clients',
		'oauth_access_tokens',
		'oauth_refresh_tokens',
		'oauth_auth_codes',
		'devcraft_migrations',
		'devcraft_logs',
		'devcraft_composer_data',
	];

	/** @var list<string> */
	private array $cols;

	/** @var array<string, mixed> */
	private array $defs;

	/** @var string|list<string> */
	private string|array $pk;

	/**
	 * @param list<string> $columns
	 * @param array<string, mixed> $defaults
	 * @param string|list<string> $primaryKey
	 */
	private function __construct(
		private readonly string $logical,
		array $columns,
		array $defaults,
		string|array $primaryKey,
	) {
		$this->cols = $columns;
		$this->defs = $defaults;
		$this->pk   = $primaryKey;
	}

	public static function isDenied(string $logical): bool {
		if(in_array($logical, self::DENY, true)) {
			return true;
		}
		if(str_starts_with($logical, 'oauth_') || str_starts_with($logical, 'api_oauth_')) {
			return true;
		}

		return false;
	}

	public static function tryFromPhysical(string $logical): ?self {
		$logical = trim($logical);
		if($logical === '' || self::isDenied($logical) || !preg_match('/^[a-zA-Z0-9_]+$/', $logical)) {
			return null;
		}
		if(isset(self::$cache[$logical])) {
			return clone self::$cache[$logical];
		}

		$phys = dle_api_table($logical);
		try {
			$rows = dle_api_db()->query('SHOW COLUMNS FROM `' . str_replace('`', '``', $phys) . '`')->fetchAll();
		} catch(\Throwable) {
			return null;
		}
		if(!is_array($rows) || $rows === []) {
			return null;
		}

		$columns = [];
		$defaults = [];
		$pri = [];
		foreach($rows as $row) {
			$field = (string) ($row['Field'] ?? $row['field'] ?? '');
			if($field === '') {
				continue;
			}
			$columns[] = $field;
			$defaults[$field] = $row['Default'] ?? ($row['default'] ?? null);
			$key = (string) ($row['Key'] ?? $row['key'] ?? '');
			if($key === 'PRI') {
				$pri[] = $field;
			}
		}
		if($columns === []) {
			return null;
		}
		$pk = match (count($pri)) {
			0       => $columns[0],
			1       => $pri[0],
			default => $pri,
		};

		$self = new self($logical, $columns, $defaults, $pk);
		self::$cache[$logical] = $self;

		return clone $self;
	}

	public function table(): string {
		return $this->logical;
	}

	protected function columnList(): array {
		return $this->cols;
	}

	protected function defaultMap(): array {
		return $this->defs;
	}

	public function primaryKey(): string|array {
		return $this->pk;
	}

	public function relations(): array {
		return [];
	}
}
