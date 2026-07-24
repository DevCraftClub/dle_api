<?php

declare(strict_types=1);

namespace DleApi\Fluent;

use DleApi\Schema\SchemaRegistry;
use DleApi\Schema\TableSchemaInterface;

/**
 * Универсальный SELECT с фильтрами по схеме и виртуальным FK (RelationMap).
 */
final class TableQuery {
	private TableSchemaInterface $schema;

	/** @var list<array{sql: string, params: list<mixed>}> */
	private array $clauses = [];

	/** @var list<string> */
	private array $selectColumns = [];

	/** @var list<array{0: string, 1: string}> */
	private array $orderClauses = [];

	private int $limit = 20;

	private int $offset = 0;

	public function __construct(private string $table, ?TableSchemaInterface $schema = null) {
		$this->schema = $schema ?? SchemaRegistry::get($table);
		if($schema !== null) {
			$this->table = $schema->table();
		}
	}

	public static function of(string $table): self {
		return new self($table);
	}

	public static function ofSchema(TableSchemaInterface $schema): self {
		return new self($schema->table(), $schema);
	}

	public function table(): string {
		return $this->table;
	}

	public function schema(): TableSchemaInterface {
		return $this->schema;
	}

	/**
	 * @param list<string> $columns
	 */
	public function select(array $columns): self {
		$allowed = $this->schema->columns();
		$this->selectColumns = [];
		foreach($columns as $col) {
			$col = (string) $col;
			if(in_array($col, $allowed, true)) {
				$this->selectColumns[] = $col;
			}
		}

		return $this;
	}

	/**
	 * Фильтр по колонке схемы. Префиксы значения: ! (не), % (LIKE).
	 */
	public function where(string $column, string $value): self {
		if(!in_array($column, $this->schema->columns(), true)) {
			throw new \InvalidArgumentException("Неизвестная колонка «{$column}» для таблицы {$this->table}");
		}
		[$mode, $val] = self::parseMode($value);
		$edge = RelationMap::edge($this->table, $column);
		if($mode === 'like') {
			$this->clauses[] = [
				'sql'    => '`' . $column . '` LIKE ?',
				'params' => ['%' . $val . '%'],
			];

			return $this;
		}
		if($edge !== null) {
			$this->addRelationClause($column, $edge['kind'], $mode, $val);

			return $this;
		}
		$op = $mode === 'neq' ? '<>' : '=';
		$this->clauses[] = [
			'sql'    => '`' . $column . '` ' . $op . ' ?',
			'params' => [$val],
		];

		return $this;
	}

	public function whereXfield(string $name, string $value): self {
		if(!in_array('xfields', $this->schema->columns(), true)) {
			throw new \InvalidArgumentException("Таблица {$this->table} не имеет колонки xfields");
		}
		if($name === '' || !preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
			throw new \InvalidArgumentException('Некорректное имя доп. поля');
		}
		$this->clauses[] = [
			'sql'    => "CONCAT('||', `xfields`, '||') LIKE CONCAT('%||', ?, '|', ?, '||%')",
			'params' => [$name, $value],
		];

		return $this;
	}

	public function orderBy(string $column, string $sort = 'DESC'): self {
		if($column !== '' && in_array($column, $this->schema->columns(), true)) {
			$dir = strtoupper($sort) === 'ASC' ? 'ASC' : 'DESC';
			$this->orderClauses[] = [$column, $dir];
		}

		return $this;
	}

	/**
	 * @param array<string, string> $order колонка => ASC|DESC
	 */
	public function orderMap(array $order): self {
		foreach($order as $column => $sort) {
			$this->orderBy((string) $column, (string) $sort);
		}

		return $this;
	}

	public function limit(int $limit): self {
		$this->limit = max(1, min(200, $limit));

		return $this;
	}

	public function offset(int $offset): self {
		$this->offset = max(0, $offset);

		return $this;
	}

	/**
	 * @return list<array<string, mixed>>
	 */
	public function fetchAll(): array {
		$compiled = $this->compileSelect();
		$cacheKey = null;
		if(class_exists(\DevCraft\Core\Cache\CacheControl::class)) {
			$cacheKey = hash('sha256', $this->table . '|' . $compiled['sql'] . '|' . json_encode($compiled['params']));
			try {
				\DevCraft\Core\Cache\CacheControl::init();
				$cached = \DevCraft\Core\Cache\CacheControl::getCache('dle_api_query', $cacheKey);
				if(is_array($cached)) {
					return $cached;
				}
			} catch(\Throwable) {
			}
		}

		$rows = dle_api_db()->query($compiled['sql'], $compiled['params'])->fetchAll();
		$rows = is_array($rows) ? $rows : [];

		if($cacheKey !== null && class_exists(\DevCraft\Core\Cache\CacheControl::class)) {
			try {
				\DevCraft\Core\Cache\CacheControl::setCache('dle_api_query', $cacheKey, $rows);
			} catch(\Throwable) {
			}
		}

		return $rows;
	}

	/**
	 * @return array<string, mixed>|null
	 */
	public function find(int|string $id): ?array {
		$cacheKey = null;
		if(class_exists(\DevCraft\Core\Cache\CacheControl::class)) {
			$cacheKey = hash('sha256', $this->table . '|find|' . $id);
			try {
				\DevCraft\Core\Cache\CacheControl::init();
				$cached = \DevCraft\Core\Cache\CacheControl::getCache('dle_api_query', $cacheKey);
				if(is_array($cached) || $cached === null) {
					return $cached;
				}
			} catch(\Throwable) {
			}
		}

		$row = dle_api_find($this->table, $id);

		if($cacheKey !== null && class_exists(\DevCraft\Core\Cache\CacheControl::class) && $row !== null) {
			try {
				\DevCraft\Core\Cache\CacheControl::setCache('dle_api_query', $cacheKey, $row);
			} catch(\Throwable) {
			}
		}

		return $row;
	}

	/**
	 * @return array{sql: string, params: list<mixed>}
	 */
	public function compileSelect(): array {
		$phys   = dle_api_table($this->table);
		$where  = ['1=1'];
		$params = [];
		foreach($this->clauses as $c) {
			$where[] = $c['sql'];
			foreach($c['params'] as $p) {
				$params[] = $p;
			}
		}
		$pk = is_string($this->schema->primaryKey())
			? $this->schema->primaryKey()
			: ($this->schema->columns()[0] ?? 'id');

		if($this->selectColumns !== []) {
			$select = implode(', ', array_map(static fn(string $c) => '`' . $c . '`', $this->selectColumns));
		} else {
			$select = '*';
		}

		$orderSql = [];
		foreach($this->orderClauses as [$col, $dir]) {
			$orderSql[] = '`' . $col . '` ' . $dir;
		}
		if($orderSql === []) {
			$orderSql[] = '`' . $pk . '` DESC';
		}

		$sql = 'SELECT ' . $select . ' FROM ' . $phys
			. ' WHERE ' . implode(' AND ', $where)
			. ' ORDER BY ' . implode(', ', $orderSql)
			. ' LIMIT ' . $this->limit . ' OFFSET ' . $this->offset;

		return ['sql' => $sql, 'params' => $params];
	}

	/**
	 * @return array{sql: string, params: list<mixed>}
	 */
	public function compileWhere(): array {
		$parts  = [];
		$params = [];
		foreach($this->clauses as $c) {
			$parts[] = $c['sql'];
			foreach($c['params'] as $p) {
				$params[] = $p;
			}
		}

		return [
			'sql'    => $parts === [] ? '1=1' : implode(' AND ', $parts),
			'params' => $params,
		];
	}

	private function addRelationClause(string $column, string $kind, string $mode, string $val): void {
		$neg = $mode === 'neq';
		if($kind === RelationMap::KIND_CSV || $kind === RelationMap::KIND_CSV_OR_ALL) {
			if($kind === RelationMap::KIND_CSV_OR_ALL && $val === 'all') {
				$sql    = "(FIND_IN_SET(?, `{$column}`) OR `{$column}` = ?)";
				$params = ['all', 'all'];
			} else {
				$sql    = "FIND_IN_SET(?, `{$column}`)";
				$params = [$val];
			}
			if($this->table === 'post' && $column === 'category') {
				$pk   = is_string($this->schema->primaryKey()) ? $this->schema->primaryKey() : 'id';
				$cats = dle_api_table('post_extras_cats');
				$sql  = '(' . $sql . " OR EXISTS (SELECT 1 FROM {$cats} pec WHERE pec.news_id = `{$pk}` AND pec.cat_id = ?))";
				$params[] = (int) $val > 0 ? (int) $val : $val;
			}
			if($neg) {
				$sql = 'NOT (' . $sql . ')';
			}
			$this->clauses[] = ['sql' => $sql, 'params' => $params];

			return;
		}
		$op = $neg ? '<>' : '=';
		$this->clauses[] = [
			'sql'    => '`' . $column . '` ' . $op . ' ?',
			'params' => [$val],
		];
	}

	/**
	 * @return array{0: 'eq'|'neq'|'like', 1: string}
	 */
	private static function parseMode(string $raw): array {
		if($raw !== '' && $raw[0] === '!') {
			return ['neq', substr($raw, 1)];
		}
		if($raw !== '' && $raw[0] === '%') {
			return ['like', substr($raw, 1)];
		}

		return ['eq', $raw];
	}
}
