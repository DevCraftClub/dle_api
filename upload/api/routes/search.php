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

		if (!$api_name) return ErrorResponse::response(
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
			return ErrorResponse::response($response, 405, $checkAccess['error']);
		}

		$access = [];

		$access['full']     = $checkAccess['admin'];
		$access['can_read'] = $checkAccess['read'];

		if (!$access['full'] || !$access['can_read']) {
			return ErrorResponse::response($response, 403, 'Недостаточно прав доступа!');
		}

		if (is_array($params['attribute'])) {
			$t_attr = sanitizeArray($params['attribute']);
		} else {
			$t_attr = filter_var($params['attribute'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}

		if (is_array($params['value'])) {
			$t_attr_val = sanitizeArray($params['value']);
		} else {
			$t_attr_val = filter_var($params['value'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}

		if (is_array($params['max'])) {
			$t_attr_max = sanitizeArray($params['max'], FILTER_VALIDATE_INT);
		} else {
			$t_attr_max = filter_var($params['max'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}

		if (is_array($params['min'])) {
			$t_attr_min = sanitizeArray($params['min'], FILTER_VALIDATE_INT);
		} else {
			$t_attr_min = filter_var($params['min'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}

		if (is_array($params['compare'])) {
			$t_attr_compare = sanitizeArray($params['compare']);
		} else {
			$t_attr_compare = filter_var($params['compare'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}

		$t_attr_f_bind = filter_var($params['field_bind'], FILTER_SANITIZE_FULL_SPECIAL_CHARS | CASE_UPPER);
		$t_attr_select = filter_var($params['select'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$t_attr_order  = filter_var($params['orderby'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$t_attr_sort   = filter_var($params['sort'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$t_attr_limit  = filter_var($params['limit'], FILTER_VALIDATE_INT);

		if (empty($t_attr) || (is_array($t_attr) && count($t_attr) == 0)) return ErrorResponse::response(
			$response,
			400,
			'Не верно указан параметр attribute в запросе! Пример: /search/post/?attribute=id'
		);

		if (empty($t_attr_val) && (!$t_attr_max || !$t_attr_min)) return ErrorResponse::response(
			$response,
			400,
			'Не верно указан параметр value / max / min в запросе! Пример: /search/post/?attribute=id&value=1&max=10&min=1. Можно указать как один из параметров, так и скомбинировать. Однако при указании min / max, значение value игнорируется!'
		);

		if (is_array($t_attr) && empty($t_attr_f_bind)) $t_attr_f_bind = 'AND';
		if (!in_array($t_attr_f_bind, ['AND', 'OR'])) return ErrorResponse::response(
			$response,
			400,
			'Не верно указан параметр field_bind в запросе! Пример: /search/post/?attribute[]=id&attribute[]=title&field_bind=and. Доступны: AND, OR'
		);

		if (is_array($t_attr)) {
			$items = count($t_attr);

			// Формируем массивы для проверки
			$arraysToCheck = [
				'value' => is_array($t_attr_val) ? count($t_attr_val) : 0,
				'min'   => is_array($t_attr_min) ? count($t_attr_min) : 0,
				'max'   => is_array($t_attr_max) ? count($t_attr_max) : 0,
			];

			// Суммируем количество заполненных массивов
			$total = array_sum($arraysToCheck);

			// Если итоговая сумма не совпадает с количеством $t_attr
			if ($total >= $items) {
				$missing     = array_filter($arraysToCheck, fn($count) => $count > 0);
				$missingKeys = implode(', ', array_keys($missing));

				return ErrorResponse::response(
					$response,
					400,
					"Количество значений {$missingKeys} должно быть таким же, как и attribute!"
				);
			}
		}

		if (!is_array($t_attr)) $t_attr = [$t_attr];

		$sql_params   = [];
		$search_value = [];

		for ($i = 0; $i < count($t_attr); $i++) {
			if (!is_array($t_attr_compare) && empty($t_attr_compare)) $attr_compare = 'eq';
			else if (!is_array($t_attr_compare) && !empty($t_attr_compare)) $attr_compare = $t_attr_compare;
			else if (is_array($t_attr_compare)) $attr_compare = $t_attr_compare[$i];

			if (!is_array($t_attr_max) && !empty($t_attr_max)) $max = $t_attr_max;
			else if (!is_array($t_attr_max) && empty($t_attr_max)) $max = null;
			else if (is_array($t_attr_max)) $max = $t_attr_max[$i] ?: null;

			if (!is_array($t_attr_min) && !empty($t_attr_min)) $min = $t_attr_min;
			else if (!is_array($t_attr_min) && empty($t_attr_min)) $min = null;
			else if (is_array($t_attr_min)) $min = $t_attr_min[$i] ?: null;

			if (!is_array($t_attr_val) && !empty($t_attr_val)) $value = $t_attr_val;
			else if (!is_array($t_attr_val) && empty($t_attr_val)) $value = null;
			else if (is_array($t_attr_val)) $value = $t_attr_val[$i] ?: null;

			$attr = $t_attr[$i];

			if (!$value && !$max && !$min)
				return ErrorResponse::response($response, 400, 'Значения value, min и / или max должны быть заполнены!');

			if (!in_array($attr_compare, ['eq', 'g', 'l', 'ge', 'le', 'neq', 'like']))
				return ErrorResponse::response(
					$response,
					400,
					'Не верно указан параметр compare в запросе! Пример: /search/post/?attribute=id&value=1&compare=eq. Доступны: eq, neq, gt, lt, gte, lte, like'
				);

			$compare_sign = match ($attr_compare) {
				'eq'    => '=',
				'gt'    => '<',
				'lt'    => '>',
				'gte'   => '<=',
				'lte'   => '>=',
				'neq'   => '<>',
				'like'  => 'LIKE',
				default => '='
			};

			if ($max && $min) {
				$compare_sign      = in_array($attr_compare, ['gte', 'lte']) ? '<=' : '<';
				$search_value[]    = "? {$compare_sign} {$attr} {$compare_sign} ?";
				$sql_params[] = min([$min, $max]);
				$sql_params[] = max([$min, $max]);
			} else if (!$min && $max) {
				$search_value[]    = "{$attr} {$compare_sign} ?";
				$sql_params[] = $max;
			} else if ($min && !$max) {
				$search_value[]      = "{$attr} {$compare_sign} ?";
				$sql_params[] = $min;
			} else if ($attr_compare == 'like') {
				if (in_array($attr, ['xfields'])) {
					if ($attr === 'xfields') {
						[$name, $val] = explode('|', str_replace(['&amp;', '&#124;'], ['&', '|'], $value));
						$search_value[] = "{$attr} {$compare_sign} ? OR LOCATE(?, {$attr}) > 0 OR {$attr} REGEXP ?";
						$sql_params[] = "%{$value}%";
						$sql_params[] = $value;
						$sql_params[] = "{$name}.*{$val}|{$val}.*{$name}";
					}

				} else {
					$search_value[] = "{$attr} {$compare_sign} ? OR LOCATE(?, {$attr}) > 0";
					$sql_params[] = "%{$value}%";
					$sql_params[] = $value;
				}

			} else {
				$search_value[]      = "{$attr} {$compare_sign} :val";
				$sql_params['val'] = $value;
			}

		}

		if (empty($t_attr_select)) $t_attr_select = '*';
		if (empty($t_attr_sort)) $t_attr_sort = 'DESC';

		$prefix  = in_array($api_name, ['users', 'users_delete', 'usergroups']) ? $USERprefix : $DLEprefix;
		$orderBy = $t_attr_order ? "ORDER BY {$t_attr_order}" : '';
		$orderBy = $t_attr_sort && strlen($orderBy) != 0 ? "{$orderBy} {$t_attr_sort}" : $orderBy;
		$limit   = $t_attr_limit ? "LIMIT {$t_attr_limit}" : '';

		if (count($search_value) > 1) {
			foreach ($search_value as $key => $value) {
				$search_value[$key] = "({$value})";
			}
		}

		$search_data = implode(" {$t_attr_f_bind} ", $search_value);

		$sql = "SELECT {$t_attr_select} FROM {$prefix}_{$api_name} WHERE {$search_data} {$orderBy} {$limit}";

		$getData = new CacheSystem($api_name, $sql . implode(' ', $sql_params));
		if (check_response($getData->get())) {
			try {
				$data = $connect::select($sql, $sql_params);
			} catch (Exception $e) {
				return ErrorResponse::response($response, 400, $e->getMessage());
			}

			$getData->setData($data);
			$data = $getData->create();
		} else {
			$data = $getData->get();
		}

		return ErrorResponse::success($response, $data, 200);
	}
);
