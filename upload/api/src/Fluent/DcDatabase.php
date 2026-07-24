<?php

declare(strict_types=1);

/**
 * Тонкие обёртки над DevCraft DatabaseGateway (Cycle ORM).
 * Единственный источник БД: Application::instance()->database().
 */

use DevCraft\Core\Application;
use DevCraft\Core\Database\DatabaseGateway;
use DleApi\Schema\SchemaRegistry;

if(!class_exists(\JetBrains\PhpStorm\ExpectedValues::class, false)) {
	$path = __DIR__ . '/../Support/PhpStorm/ExpectedValues.php';
	require_once class_exists('DLEPlugins', false) ? DLEPlugins::Check($path) : $path;
}

if(!function_exists('dle_api_db')) {
	/**
	 * Возвращает шлюз Cycle ORM через DevCraft Admin (DC AP).
	 */
	function dle_api_db(): DatabaseGateway {
		return Application::instance()->database();
	}
}

if(!function_exists('dle_api_prefix')) {
	function dle_api_prefix(): string {
		return defined('PREFIX') ? (string) PREFIX : 'dle';
	}
}

if(!function_exists('dle_api_user_prefix')) {
	function dle_api_user_prefix(): string {
		return defined('USERPREFIX') ? (string) USERPREFIX : dle_api_prefix();
	}
}

if(!function_exists('dle_api_table')) {
	/**
	 * Физическое имя таблицы с префиксом.
	 */
	function dle_api_table(string $logical): string {
		$prefix = ($logical === 'users' || $logical === 'users_delete' || $logical === 'usergroups')
			? dle_api_user_prefix()
			: dle_api_prefix();

		return $prefix . '_' . $logical;
	}
}

if(!function_exists('dle_api_insert')) {
	/**
	 * INSERT через Cycle (DC DatabaseGateway). Возвращает last insert id.
	 *
	 * @param array<string, mixed> $row
	 */
	function dle_api_insert(string $logical, array $row): int {
		$schema = dle_api_resolve_schema($logical);
		$row    = $schema->showColumns($row);
		$pk     = $schema->primaryKey();
		$pks    = is_array($pk) ? $pk : [$pk];
		foreach($pks as $key) {
			// PK с автоинкрементом не передаём, если пустой/0
			if(array_key_exists($key, $row) && ($row[$key] === null || $row[$key] === '' || $row[$key] === 0 || $row[$key] === '0')) {
				unset($row[$key]);
			}
		}
		if($row === []) {
			throw new RuntimeException("Пустой INSERT для таблицы {$logical}");
		}

		$id = dle_api_db()->connection()->insert(dle_api_table($logical))->values($row)->run();

		return (int) $id;
	}
}

if(!function_exists('dle_api_update')) {
	/**
	 * UPDATE через Cycle (DC DatabaseGateway, параметризованный SQL).
	 *
	 * @param array<string, mixed> $set
	 * @param array<int|string, mixed> $params
	 */
	function dle_api_update(string $logical, array $set, string $whereSql, array $params = []): void {
		$schema = dle_api_resolve_schema($logical);
		$set    = $schema->showColumns($set);
		if($set === []) {
			return;
		}
		$parts = [];
		$bind  = [];
		$i     = 0;
		foreach($set as $col => $val) {
			$ph         = 's' . $i++;
			$parts[]    = '`' . $col . '` = ?';
			$bind[]     = $val;
		}
		foreach($params as $p) {
			$bind[] = $p;
		}
		$sql = 'UPDATE ' . dle_api_table($logical) . ' SET ' . implode(', ', $parts) . ' WHERE ' . $whereSql;
		dle_api_db()->query($sql, $bind);
	}
}

if(!function_exists('dle_api_resolve_schema')) {
	/**
	 * SchemaRegistry или introspected physical (для CRUD-хелперов, не для SDK Registry::get).
	 */
	function dle_api_resolve_schema(string $logical): \DleApi\Schema\TableSchemaInterface {
		try {
			return SchemaRegistry::get($logical);
		} catch(\InvalidArgumentException) {
			$intro = \DleApi\Schema\IntrospectedTableSchema::tryFromPhysical($logical);
			if($intro === null) {
				throw new InvalidArgumentException("Неизвестная таблица Schema: {$logical}");
			}

			return $intro;
		}
	}
}

if(!function_exists('dle_api_scalar_pk')) {
	/**
	 * Имя одиночного PK или null (составной ключ).
	 */
	function dle_api_scalar_pk(string $logical): ?string {
		$pk = dle_api_resolve_schema($logical)->primaryKey();

		return is_string($pk) ? $pk : null;
	}
}

if(!function_exists('dle_api_find')) {
	/**
	 * SELECT одной строки по скалярному PK.
	 *
	 * @return array<string, mixed>|null
	 */
	function dle_api_find(string $logical, int|string $id): ?array {
		$pk = dle_api_scalar_pk($logical);
		if($pk === null) {
			throw new RuntimeException("Таблица {$logical} имеет составной PK — GET/PUT/DELETE по /{id} не поддерживается");
		}
		$row = dle_api_db()->query(
			'SELECT * FROM ' . dle_api_table($logical) . ' WHERE `' . $pk . '` = ? LIMIT 1',
			[$id],
		)->fetch();

		return is_array($row) ? $row : null;
	}
}

if(!function_exists('dle_api_list')) {
	/**
	 * SELECT список строк.
	 *
	 * @param array<string, string> $order            колонка => ASC|DESC
	 * @param list<string>          $selectedColumns  [] = *
	 * @param array<string, mixed>  $attrs            WHERE равенства (строковые значения)
	 * @return list<array<string, mixed>>
	 */
	function dle_api_list(
		string $logical,
		int $limit = 20,
		int $offset = 0,
		array $order = [],
		array $selectedColumns = [],
		array $attrs = [],
	): array {
		$q = \DleApi\Fluent\TableQuery::ofSchema(dle_api_resolve_schema($logical))
			->limit($limit)
			->offset($offset);
		if($selectedColumns !== []) {
			$q->select($selectedColumns);
		}
		if($order !== []) {
			$q->orderMap($order);
		}
		foreach($attrs as $col => $val) {
			if(is_scalar($val) || $val === null) {
				$q->where((string) $col, (string) ($val ?? ''));
			}
		}

		return $q->fetchAll();
	}
}

if(!function_exists('dle_api_query')) {
	function dle_api_query(string $logical): \DleApi\Fluent\TableQuery {
		return \DleApi\Fluent\TableQuery::of($logical);
	}
}

if(!function_exists('dle_api_sync_post_categories')) {
	/**
	 * Синхронизация dle_post_extras_cats для новости.
	 *
	 * @param list<int> $catIds
	 */
	function dle_api_sync_post_categories(int $newsId, array $catIds): void {
		$db   = dle_api_db();
		$cats = dle_api_table('post_extras_cats');
		$db->query('DELETE FROM ' . $cats . ' WHERE news_id = ?', [$newsId]);
		foreach($catIds as $catId) {
			$catId = (int) $catId;
			if($catId < 1) {
				continue;
			}
			$db->query('INSERT INTO ' . $cats . ' (news_id, cat_id) VALUES (?, ?)', [$newsId, $catId]);
		}
	}
}

if(!function_exists('dle_api_update_by_pk')) {
	/**
	 * UPDATE по скалярному PK.
	 *
	 * @param array<string, mixed> $set
	 */
	function dle_api_update_by_pk(string $logical, int|string $id, array $set): void {
		$pk = dle_api_scalar_pk($logical);
		if($pk === null) {
			throw new RuntimeException("Таблица {$logical} имеет составной PK — PUT по /{id} не поддерживается");
		}
		unset($set[$pk]);
		dle_api_update($logical, $set, '`' . $pk . '` = ?', [$id]);
	}
}

if(!function_exists('dle_api_delete_by_pk')) {
	/**
	 * DELETE по скалярному PK.
	 */
	function dle_api_delete_by_pk(string $logical, int|string $id): void {
		$pk = dle_api_scalar_pk($logical);
		if($pk === null) {
			throw new RuntimeException("Таблица {$logical} имеет составной PK — DELETE по /{id} не поддерживается");
		}
		dle_api_db()->query(
			'DELETE FROM ' . dle_api_table($logical) . ' WHERE `' . $pk . '` = ?',
			[$id],
		);
	}
}
