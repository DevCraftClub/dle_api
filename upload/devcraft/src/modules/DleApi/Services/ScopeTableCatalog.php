<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Services;

use DevCraft\Core\Application;

/**
 * Список логических имён таблиц для матрицы scope (как в legacy: SHOW TABLES).
 *
 * Включает таблицы ядра и внешних плагинов; SchemaRegistry намеренно не ограничивает список.
 */
final class ScopeTableCatalog {

	/** @var list<string>|null */
	private static ?array $cache = null;

	private const SKIP = [
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
	];

	/**
	 * @return list<string>
	 */
	public function names(): array {
		if(self::$cache !== null) {
			return self::$cache;
		}

		$prefix     = defined('PREFIX') ? PREFIX . '_' : '';
		$userPrefix = defined('USERPREFIX') ? USERPREFIX . '_' : '';
		$names      = [];

		try {
			$stmt = Application::instance()->database()->query('SHOW TABLES');
			$rows = $stmt->fetchAll(\PDO::FETCH_NUM);
		} catch(\Throwable) {
			self::$cache = [];

			return self::$cache;
		}

		foreach($rows as $row) {
			$physical = (string) ($row[0] ?? '');

			if($physical === '') {
				continue;
			}

			$name = $physical;

			if($prefix !== '' && str_starts_with($name, $prefix)) {
				$name = substr($name, strlen($prefix));
			} elseif($userPrefix !== '' && $userPrefix !== $prefix && str_starts_with($name, $userPrefix)) {
				$name = substr($name, strlen($userPrefix));
			}

			if(in_array($name, self::SKIP, true)) {
				continue;
			}

			$names[$name] = $name;
		}

		$names = array_values($names);
		sort($names, SORT_STRING);

		self::$cache = $names;

		return self::$cache;
	}

}
