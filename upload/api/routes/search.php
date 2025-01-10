<?php

global $app, $connect;

if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Обработчик маршрута `/search/{db_table}[/]`, отвечающий за выполнение поиска в указанной таблице базы данных.
 *
 * @param ServerRequestInterface $request  HTTP-запрос.
 * @param ResponseInterface      $response HTTP-ответ.
 * @param array                  $args     Аргументы маршрута, где 'db_table' — имя таблицы для поиска.
 *
 * @return ResponseInterface Ответ с результатами поиска, либо информация об ошибке.
 *
 * Ошибки:
 * - 400: Неправильно указана таблица базы данных, параметр 'attribute' или параметр 'value / max / min'.
 * - 405: Ошибка доступа. Некорректный API-ключ или отсутствие прав доступа.
 * - 403: Недостаточно прав доступа для выполнения операции.
 * - 200: Успешный ответ с данными.
 *
 * Алгоритм:
 * 1. Валидирует указание имени таблицы базы данных.
 * 2. Проверяет доступ по API-ключу с определением прав доступа.
 * 3. Извлекает параметры запроса, такие как `attribute`, `value`, `max`, `min`, `compare`, `sort`, `orderby`, `limit`,
 * и валидирует их.
 * 4. Формирует SQL-запрос на основе входных параметров.
 * 5. Выполняет поиск в базе данных, используя предварительно построенный SQL-запрос.
 * 6. Кеширует данные, если результаты поиска отсутствуют в кеше.
 * 7. Возвращает данные в формате JSON.
 *
 * @throws Если параметры запроса некорректны, возвращается ошибка с соответствующим HTTP-кодом.
 */
$app->get(
	'/search/{db_table}[/]',
	function (ServerRequestInterface $request, ResponseInterface $response, array $args) use ($connect) {
		global $DLEprefix, $USERprefix;

		$api_name = filter_var($args['db_table'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		if (!$api_name) return ErrorResponse::error(
			$response,
			400,
			'Не (верно) указана таблица в базе данных! Пример: /search/post'
		);

		$Cruds = new CrudController($api_name, []);

		$header  = $Cruds->parseHeader($request);
		$params  = $request->getQueryParams() ?: [];
		$api_key = $Cruds->extractApiKey($params, $header);

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) {
			return ErrorResponse::error($response, 405, $checkAccess['error']);
		}

		$access = [];

		$access['full']     = $checkAccess['admin'];
		$access['can_read'] = $checkAccess['read'];

		if (!$access['full'] || !$access['can_read']) {
			return ErrorResponse::error($response, 403, 'Недостаточно прав доступа!');
		}

		$t_attr         = filter_var($params['attribute'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$t_attr_val     = filter_var($params['value'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$t_attr_max     = filter_var($params['max'], FILTER_VALIDATE_INT);
		$t_attr_min     = filter_var($params['max'], FILTER_VALIDATE_INT);
		$t_attr_compare = filter_var($params['compare'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$t_attr_select  = filter_var($params['select'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$t_attr_order   = filter_var($params['orderby'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$t_attr_sort    = filter_var($params['sort'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$t_attr_limit   = filter_var($params['limit'], FILTER_VALIDATE_INT);

		if (empty($t_attr)) return ErrorResponse::error(
			$response,
			400,
			'Не верно указан параметр attribute в запросе! Пример: /search/post/?attribute=id'
		);

		if (empty($t_attr_val) && (!$t_attr_max || !$t_attr_min)) return ErrorResponse::error(
			$response,
			400,
			'Не верно указан параметр value / max / min в запросе! Пример: /search/post/?attribute=id&value=1&max=10&min=1. Можно указать как один из параметров, так и скомбинировать. Однако при указании min / max, значение value игнорируется!'
		);

		if (empty($t_attr_compare)) $t_attr_compare = 'eq';
		if (empty($t_attr_select)) $t_attr_select = '*';
		if (empty($t_attr_sort)) $t_attr_sort = 'DESC';

		if (!in_array($t_attr_compare, ['eq', 'g', 'l', 'ge', 'le', 'neq', 'like'])) return ErrorResponse::error(
			$response,
			400,
			'Не верно указан параметр compare в запросе! Пример: /search/post/?attribute=id&value=1&compare=eq. Доступны: eq, neq, gt, lt, gte, lte, like'
		);

		$compare_sign = match ($t_attr_compare) {
			'eq'    => '=',
			'gt'    => '<',
			'lt'    => '>',
			'gte'   => '<=',
			'lte'   => '>=',
			'neq'   => '<>',
			'like'  => 'LIKE',
			default => '='
		};

		$sql_params = [];

		if ($t_attr_max && $t_attr_min) {
			$compare_sign      = in_array($t_attr_compare, ['gte', 'lte']) ? '<=' : '<';
			$search_value      = ":min {$compare_sign} {$t_attr} {$compare_sign} :max";
			$sql_params['min'] = min([$t_attr_min, $t_attr_max]);
			$sql_params['max'] = max([$t_attr_min, $t_attr_max]);
		} else if (!$t_attr_min && $t_attr_max) {
			$search_value      = "{$t_attr} {$compare_sign} :max";
			$sql_params['max'] = $t_attr_max;
		} else if ($t_attr_min && !$t_attr_max) {
			$search_value      = "{$t_attr} {$compare_sign} :min";
			$sql_params['min'] = $t_attr_min;
		} else if ($t_attr_compare == 'like') {
			if (in_array($t_attr, ['xfields'])) {
				if ($t_attr === 'xfields') {
					[$name, $val] = explode('|', str_replace(['&amp;', '&#124;'], ['&', '|'], $t_attr_val));
					$search_value = "{$t_attr} {$compare_sign} ? OR LOCATE(?, {$t_attr}) > 0 OR {$t_attr} REGEXP ?";
					$sql_params[] = "%{$t_attr_val}%";
					$sql_params[] = $t_attr_val;
					$sql_params[] = "{$name}.*{$val}|{$val}.*{$name}";
				}

			} else {
				$search_value = "{$t_attr} {$compare_sign} ? OR LOCATE(?, {$t_attr}) > 0";
				$sql_params[] = "%{$t_attr_val}%";
				$sql_params[] = $t_attr_val;
			}

		} else {
			$search_value      = "{$t_attr} {$compare_sign} :val";
			$sql_params['val'] = $t_attr_val;
		}

		$prefix  = in_array($api_name, ['users', 'users_delete', 'usergroups']) ? $USERprefix : $DLEprefix;
		$orderBy = $t_attr_order ? "ORDER BY {$t_attr_order}" : '';
		$orderBy = $t_attr_sort && strlen($orderBy) != 0 ? "{$orderBy} {$t_attr_sort}" : $orderBy;
		$limit   = $t_attr_limit ? "LIMIT {$t_attr_limit}" : '';

		$sql = "SELECT {$t_attr_select} FROM {$prefix}_{$api_name} WHERE {$search_value} {$orderBy} {$limit}";

		$getData = new CacheSystem($api_name, $sql . implode(' ', $sql_params));
		if (check_response($getData->get())) {
			try {
				$data = $connect::select($sql, $sql_params);
			} catch (Exception $e) {
				return ErrorResponse::error($response, 400, $e->getMessage());
			}

			$getData->setData($data);
			$data = $getData->create();
		} else {
			$data = $getData->get();
		}

		return ErrorResponse::success($response, $data, 200);
	}
);
