<?php
//===============================================================
// Файл: functions.php                                          =
// Путь: api/includes/functions.php                             =
// Дата создания: 2024-04-08 08:42:09                           =
// Последнее изменение: 2024-04-08 08:42:08                     =
// ==============================================================
// Автор: Maxim Harder <dev@devcraft.club> © 2024               =
// Сайт: https://devcraft.club                                  =
// Телеграм: http://t.me/MaHarder                               =
// ==============================================================
// Менять на свой страх и риск!                                 =
// Код распространяется по лицензии MIT                         =
//===============================================================

global $db;
if (!defined('DATALIFEENGINE')) {
	header('HTTP/1.1 403 Forbidden');
	header('Location: ../../');
	die('Hacking attempt!');
}


include_once(DLEPlugins::Check(ENGINE_DIR . '/data/dbconfig.php'));
include_once DLEPlugins::Check(ENGINE_DIR . '/inc/includes/functions.inc.php');
include_once DLEPlugins::Check(__DIR__ . '/PDO.class.php');
include_once DLEPlugins::Check(ENGINE_DIR . '/api/api.class.php');

$dleapi      = json_decode(file_get_contents(DLEPlugins::Check(ENGINE_DIR . '/data/dleapi.json')), true);
$dle_api     = new DLE_API();
$dle_api->db = $db;

$dbHostPort = explode(':', DBHOST);
$dbHost     = $dbHostPort[0] ?: 'localhost';
$dbPort     = (isset($dbHostPort[1])) ? (int)$dbHostPort[1] : 3306;

$connect    = new database($dbHost, $dbPort, DBNAME, DBUSER, DBPASS);
$DLEprefix  = PREFIX;
$USERprefix = USERPREFIX;

/**
 * Проверяет входящее значение на тип и на знак сравнения и возвращает строкой в виде '%value%'
 *
 * @param $value
 * @param $type
 *
 * @return string
 */
function getComparer($value, $type = null): string {
	$firstSign  = ['!', '<', '>', '%'];
	$secondSign = ['='];
	$type       = gettype(defType($value, $type));
	$outSign    = '=';
	$checkSign  = null;

	if (!in_array($type, ['integer', 'double', 'boolean']) && in_array($value[0], $firstSign, true)) {
		$checkSign = $value[0];
		if (in_array($value[1], $secondSign, true)) {
			$checkSign .= $value[1];
			$value     = substr($value, 2);
		} else {
			$value = substr($value, 1);
		}
	}

	if ($checkSign === '!') {
		$outSign = '<>';
	} else if (in_array($checkSign, ['<', '>', '<=', '>='])) {
		$outSign = $checkSign;
	} else if ($checkSign === '%') {
		$outSign = 'LIKE';
		$value   = '%' . $value . '%';
	}

	$value = defType($value, $type);

	return " {$outSign} {$value}";
}

/**
 * Проверяет ключ API на действительность в базе данных
 *
 * @param $key
 * @param $name
 *
 * @return array|false[]
 */
function checkAPI($key, $name): array {
	global $connect, $DLEprefix, $USERprefix;

	$antwort = [
		'admin'  => false,
		'read'   => false,
		'view'   => false,
		'delete' => false,
	];

	try {
		if (!empty($key) && !empty($name)) {

			$keyCheck = $connect->query(
				"SELECT k.id, k.api, k.is_admin, k.active, k.user_id, k.own_only, u.name FROM  {$DLEprefix}_api_keys k, {$USERprefix}_users u WHERE k.api = :key",
				['key' => $key]
			);

			$username = (int)$keyCheck[0]['user_id'] > 0 ? $keyCheck[0]['name'] : 'Гость';

			if (!empty($keyCheck)) {
				if ($keyCheck[0]['is_admin'] && $keyCheck[0]['active'] === 1) {
					$antwort = [
						'admin'  => true,
						'read'   => true,
						'view'   => true,
						'delete' => true,
						'own'    => [
							'access'    => true,
							'user_id'   => $keyCheck[0]['user_id'],
							'user_name' => $username
						],
					];
				} else {

					$tablesCheck = $connect->query(
						"SELECT * FROM {$DLEprefix}_api_scope das WHERE das.table = :name and das.key_id = :api_id",
						['name' => $name, 'api_id' => $keyCheck[0]['id']]
					);

					if (count($tablesCheck) > 0) {
						if ($keyCheck[0]['active'] === 1) {
							if ($keyCheck[0]['is_admin'] === 1) $antwort['admin'] = true;
							if ($tablesCheck[0]['read'] === 1) $antwort['read'] = true;
							if ($tablesCheck[0]['view'] === 1) $antwort['view'] = true;
							if ($tablesCheck[0]['delete'] === 1) $antwort['delete'] = true;
							if ($keyCheck[0]['own_only'] === 1) $antwort['own']['access'] = true;
							$antwort['own']['user_id']   = $keyCheck[0]['user_id'];
							$antwort['own']['user_name'] = $username;
						} else $antwort['error'] = 'API-ключ не активен!';
					} else $antwort['error'] = 'API-ключ не действителен!';
				}
			} else $antwort['error'] = 'API-ключ не действителен!';
		} else {
			if (!isset($key)) $antwort['error'] = 'API-ключ не может быть пустым!';
			if (!isset($name)) $antwort['error'] = 'Название базы данных не может быть пустым!';
		}

		return $antwort;

	} catch (Exception $e) {
		throw new Error($e->getMessage());
	}
}

/**
 * Возвращает значение в верной типизации
 *
 * @param $value
 * @param $type
 *
 * @return bool|float|int|string
 */
function defType($value, $type = null): float|bool|int|string {

	if ($type === 'integer') $output = (int)$value;
	else if ($type === 'boolean') $output = (bool)$value;
	else if ($type === 'double') $output = (float)$value;
	else $output = "'{$value}'";

	return $output;
}

/**
 * Проверяет текст на максимальную длину и возвращает его
 *
 * @param $text
 * @param $max
 *
 * @return string
 */
function checkLength($text, $max): string {
	return (strlen($text) > $max && $max !== 0) ? substr($text, 0, $max) : $text;
}


function check_response(mixed $data): bool {
	if (is_array($data)) return count($data) > 0;
	else {
		if (!empty($data)) {
			return str_contains($data, '[]');
		}
	}
	return false;
}



/**
 * Построение фильтров SQL на основе заголовков, параметров и прав доступа.
 */
function buildFilters(array $headers, array $possibleData, array $ownAccess, bool $fullAccess): array {
	$filters = [];

	foreach ($headers as $key => $values) {
		$key = strtolower(str_replace('http_', '', $key));
		foreach ((array)$values as $value) {
			$postData = findPossibleData($key, $possibleData);

			if (!$postData) continue;

			// Если "own_only", исключить данные
			if ($key === 'autor' && !$fullAccess && $ownAccess['access']) {
				continue;
			}

			$filters[] = (string)($key) . getComparer($value, $postData['type']);
		}
	}

	return $filters;
}




