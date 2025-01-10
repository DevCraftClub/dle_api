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

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined('DATALIFEENGINE')) {
	header('HTTP/1.1 403 Forbidden');
	header('Location: ../../');
	die('Hacking attempt!');
}

include_once(DLEPlugins::Check(ENGINE_DIR . '/data/dbconfig.php'));
include_once DLEPlugins::Check(ENGINE_DIR . '/modules/functions.php');
include_once DLEPlugins::Check(ENGINE_DIR . '/api/api.class.php');

$dleapi      = json_decode(file_get_contents(DLEPlugins::Check(ENGINE_DIR . '/data/dleapi.json')), true);
$dle_api     = new DLE_API();
$dle_api->db = $db;

$dbHostPort = explode(':', DBHOST);
$dbHost     = $dbHostPort[0] ?: 'localhost';
$dbPort     = (isset($dbHostPort[1])) ? (int)$dbHostPort[1] : 3306;

$connect = new Capsule;
$connect->addConnection(
	[
		'driver'    => 'mysql',
		'host'      => $dbHost,
		'port'      => $dbPort,
		'database'  => DBNAME,
		'username'  => DBUSER,
		'password'  => DBPASS,
		'collation' => COLLATE
	]
);
$connect->setAsGlobal();
$connect->bootEloquent();
//$connect    = (new \Illuminate\Support\Facades\DB)::connection();
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

			$keyCheck = $connect::selectOne(
				"SELECT k.id, k.api, k.is_admin, k.active, k.user_id, k.own_only, u.name FROM  {$DLEprefix}_api_keys k, {$USERprefix}_users u WHERE k.api = :key",
				['key' => $key]
			);

			$user_id = filter_var($keyCheck->user_id, FILTER_VALIDATE_INT);

			$username = $user_id ? $keyCheck->name : 'Гость';

			if (!empty($keyCheck)) {
				if ($keyCheck->is_admin && $keyCheck->active === 1) {
					$antwort = [
						'admin'  => true,
						'read'   => true,
						'view'   => true,
						'delete' => true,
						'own'    => [
							'access'    => true,
							'user_id'   => $user_id,
							'user_name' => $username
						],
					];
				} else {

					$tablesCheck = $connect::select(
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

	if ($type === 'integer') $output = (int)$value; else if ($type === 'boolean') $output = (bool)$value; else if ($type === 'double') $output = (float)$value; else $output = "'{$value}'";

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
	if (is_array($data)) return count($data) > 0; else {
		if (!empty($data)) {
			return str_contains($data, '[]');
		}
	}
	return false;
}

function parseXfields(string $fields): array {
	global $lang;
	$cacheFields = new CacheSystem('xfields', $fields);
	$xf          = json_decode($cacheFields->get(), true);

	$cacheAllFields = new CacheSystem('xfields', 'all_xfields');
	$all_fields     = json_decode($cacheAllFields->get(), true);

	if (count($all_fields) == 0) {
		$path         = ENGINE_DIR . '/data/xfields.txt';
		$filecontents = file($path);

		if (count($filecontents)) {

			foreach ($filecontents as $name => $value) {
				if (trim($value)) {
					$tmp_arr                 = explode("|", trim($value, "\t\n\r\0\x0B"));
					$all_fields[$tmp_arr[0]] = [
						'id'      => trim($tmp_arr[0]),
						'name'    => trim($tmp_arr[1]),
						'type'    => trim($tmp_arr[3]),
						'linkify' => (int)$tmp_arr[17] == 1
					];
				}
			}
		}

		$cacheAllFields->setData($all_fields);
		$cacheAllFields->create();
	}

	if (count($xf) == 0) {
		foreach (explode('||', $fields) as $x) {
			[$name, $value] = explode('|', $x);
			$xf_data  = $all_fields[$name];
			$xf_value = null;

			switch ($xf_data['type']) {
				default:
					$xf_value = filter_var($value);
					break;

				case "yesorno":
					if (filter_var($value, FILTER_VALIDATE_INT)) {
						$xf_value = $lang['xfield_xyes'];
					} else {
						$xf_value = $lang['xfield_xno'];
					}
					break;

				case "datetime":
					$xf_value = strtotime(str_replace("&#58;", ":", $value));
					break;

				case "select":
					$xf_value = str_replace('&amp;#x2C;', ',', $value);
					break;

				case 'image':
					$img_data = str_replace('&#124;', '|', $value);
					$xf_value = get_uploaded_image_info($img_data);
					break;

				case 'imagegalery':
					foreach (explode(',', $value) as $img) {
						$xf_value[] = get_uploaded_image_info(str_replace('&#124;', '|', $img));
					}
					break;

				//				case 'file':
				//
				//					break;

				case 'audio':
				case 'video':
					if ($xf_data['type'] == "audio") {
						$xftag  = "audio";
						$xftype = "audio/mp3";
					} else {
						$xftag  = "video";
						$xftype = "video/mp4";
					}
					$fieldvalue_arr = explode(',', trim($value));

					foreach ($fieldvalue_arr as $temp_value) {
						$temp_array = explode('|', $temp_value);
						if (count($temp_array) < 4) {
							$temp_alt = '';
							$temp_url = $temp_array[0];
						} else {
							$temp_alt = $temp_array[0];
							$temp_url = $temp_array[1];
						}
						$filename = pathinfo($temp_url, PATHINFO_FILENAME);
						$filename = explode("_", $filename);
						if (count($filename) > 1 and intval($filename[0])) unset($filename[0]);
						$filename = implode("_", $filename);

						if (!$temp_alt) $temp_alt = $filename;
						$xf_value[] = [
							'file_name' => $filename,
							'link'      => $temp_url,
							'alt_name'  => $temp_alt,
							'tag'       => $xftag,
							'type'      => $xftype
						];
					}

					break;

			}

			$xf[$name] = $xf_value;
		}

		$cacheFields->setData($xf);
		$cacheFields->create();
	}

	return $xf;
}

function parseCategories(string $cats): array {
	global $connect, $DLEprefix;

	$all_cats_sql = "SELECT id, name FROM {$DLEprefix}_category";
	$cacheAllCats = new CacheSystem('category', $all_cats_sql);
	$all_cats     = $cacheAllCats->get();

	if (check_response($all_cats)) {
		$all_cats_db = $connect::select($all_cats_sql, []);
		$all_cats    = json_decode($all_cats, true);

		foreach ($all_cats_db as $cat) {
			$all_cats[filter_var($cat['id'], FILTER_VALIDATE_INT)] = filter_var(
				$cat['name'],
				FILTER_SANITIZE_FULL_SPECIAL_CHARS
			);
		}
		$cacheAllCats->setData($all_cats);
		$cacheAllCats->create();
	} else
		$all_cats = json_decode($all_cats, true);

	$cachePostCats = new CacheSystem('category', $cats);
	$post_cats     = $cachePostCats->get();

	if (check_response($post_cats)) {
		$post_cats = json_decode($post_cats, true);
		foreach (explode(',', $cats) as $c) {
			$id             = filter_var($c, FILTER_VALIDATE_INT);
			$post_cats[$id] = $all_cats[$id];
		}
		$cachePostCats->setData($post_cats);
		$cachePostCats->create();
	} else
		$post_cats = json_decode($post_cats, true);

	return $post_cats;
}


