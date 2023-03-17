<?php
if (!defined('DATALIFEENGINE')) {
	header('HTTP/1.1 403 Forbidden');
	header('Location: ../../');
	die('Hacking attempt!');
}


include_once(DLEPlugins::Check(API_DIR . '/vendor/autoload.php'));
include_once(DLEPlugins::Check(ENGINE_DIR . '/data/dbconfig.php'));
include_once DLEPlugins::Check(ENGINE_DIR . '/inc/includes/functions.inc.php');
include_once DLEPlugins::Check(__DIR__ . '/PDO.class.php');
$dleapi = json_decode(file_get_contents(DLEPlugins::Check(ENGINE_DIR . '/data/dleapi.json')), true);

$dbHostPort = explode(':', DBHOST);
$dbHost = $dbHostPort[0] ?: 'localhost';
$dbPort = (isset($dbHostPort[1])) ? (int) $dbHostPort[1] : 3306;

$connect    = new database($dbHost, $dbPort, DBNAME, DBUSER, DBPASS);
$DLEprefix  = PREFIX;
$USERprefix = USERPREFIX;

/**
 * Проверяет входящее значение на тип и на знак сравнения и возвращает строкой в виде '%value%'
 *
 * @param $value
 * @param $type
 * @return string
 */
function getComparer($value, $type = null) : string {
	$firstSign  = array('!', '<', '>', '%');
	$secondSign = array('=');
	$type       = gettype(defType($value, $type));
	$outSign    = '=';
	$checkSign  = NULL;

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
	} elseif (in_array($checkSign, array('<', '>', '<=', '>='))) {
		$outSign = $checkSign;
	} elseif ($checkSign === '%') {
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
 * @return array|false[]
 */
function checkAPI($key, $name) : array {
	global $connect, $DLEprefix, $USERprefix;

	$antwort = array(
		'admin'  => false,
		'read'   => false,
		'view'   => false,
		'delete' => false,
	);

	try {
		if (!empty($key) && !empty($name)) {
			$keyCheck = $connect->query("SELECT k.id, k.api, k.is_admin, k.active, u.user_id, k.own_only, u.name FROM  {$DLEprefix}_api_keys k, {$USERprefix}_users u WHERE u.user_id = k.user_id and api = :key", array('key' => $key));

			if (!empty($keyCheck)) {
				if ($keyCheck[0]['is_admin'] && $keyCheck[0]['active'] === 1) {
					$antwort = array(
						'admin'  => true,
						'read'   => true,
						'view'   => true,
						'delete' => true,
						'own'    => [
							'access'    => true,
							'user_id'   => $keyCheck[0]['user_id'],
							'user_name' => $keyCheck[0]['name']
						],
					);
				} else {

					$tablesCheck = $connect->query("SELECT * FROM {$DLEprefix}_api_scope
														WHERE table = :name and key_id = :api", array('name' => $name,
																									  'api'  => $keyCheck[0]['api']
					));

					if (count($tablesCheck) > 0) {
						if ($keyCheck[0]['active'] === 1) {
							if ($keyCheck[0]['is_admin'] === 1) $antwort['admin'] = true;
							if ($tablesCheck[0]['read'] === 1) $antwort['read'] = true;
							if ($tablesCheck[0]['view'] === 1) $antwort['view'] = true;
							if ($tablesCheck[0]['delete'] === 1) $antwort['delete'] = true;
							if ($keyCheck[0]['own_only'] === 1) $antwort['own']['access'] = true;
							$antwort['own']['user_id']   = $keyCheck[0]['user_id'];
							$antwort['own']['user_name'] = $keyCheck[0]['name'];
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
 * @return bool|float|int|string
 */
function defType($value, $type = null) : float|bool|int|string {

	if ($type === 'integer') $output = (int) $value;
	elseif ($type === 'boolean') $output = (bool) $value;
	elseif ($type === 'double') $output = (float) $value;
	else $output = "'{$value}'";

	return $output;
}

/**
 * Проверяет текст на максимальную длину и возвращает его
 *
 * @param $text
 * @param $max
 * @return string
 */
function checkLength($text, $max) : string {
	return (strlen($text) > $max && $max !== 0) ? substr($text, 0, $max) : $text;
}

/**
 * Система кеширования запросов
 */
class CacheSystem {
	private string $cachePath;
	private string $module;
	private string $id;
	private string $data;
	private string $app;

	/**
	 * CacheSystem constructor.
	 *
	 * @param        $module // Название таблицы
	 * @param string $id // Идентификационный набор символов
	 * @param mixed $data // Передаваемые и сохраняемые данные
	 * @param string $app // Тип кеша
	 * @param string $path // Путь кеша
	 */
	public function __construct(string $module, string $id = '', mixed $data = '', string $app = 'api', string $path = ENGINE_DIR . '/cache') {
		$this->data   = $data;
		$this->app    = $app;
		$this->module = $module;
		$this->id     = $id;
		if (empty($this->id)) $this->id = "{$app}_{$module}";
		$this->setCachePath($path);
	}

	/**
	 * @param string $cachePath
	 */
	public function setCachePath(string $cachePath) : void {
		if (!mkdir($cachePath) && !is_dir($cachePath)) {
			throw new \RuntimeException(sprintf('Directory "%s" was not created', $cachePath));
		}
		$this->cachePath = $cachePath;
	}

	/**
	 * @return bool|string
	 */
	public function create() : bool|string {
		$file_name = "{$this->app}_{$this->module}_" . md5($this->id) . '.json';
		file_put_contents($this->cachePath . '/' . $file_name, json_encode($this->data, JSON_UNESCAPED_UNICODE));

		return $this->get();
	}

	/**
	 * Возвращает сохранённый в кеше запрос
	 *
	 * @return string
	 */
	public function get() : string {
		$file_name = "{$this->app}_{$this->module}_" . md5($this->id) . '.json';
		if (file_exists($this->cachePath . '/' . $file_name)) {
			$return_data = json_decode(file_get_contents($this->cachePath . '/' . $file_name), true);
			foreach ($return_data as $id => $data) {
				foreach ($data as $key => $value)
					$return_data[$id][$key] = $this->secureData($key, $value);
			}
			return '';
		}

		return json_encode([], JSON_UNESCAPED_UNICODE);
	}

	/**
	 * Очищает файлы кеша
	 *
	 * @param string $app
	 */
	public function clear(string $app = '') : void {
		$pattern = (empty($app)) ? '*' : $this->app . '_' . $app . '_*';
		$pattern .= '.json';
		try {
			foreach (glob($this->cachePath . '/' . $pattern) as $filename) {
				unlink($filename);
			}
		} catch (Exception $e) {
			throw new Error($e->getMessage());
		}
	}

	/**
	 * @param mixed $data
	 */
	public function setData(mixed $data) : void {
		$this->data = $data;
	}

	/**
	 * Засекречивает данные при выводе информации
	 *
	 * @param $data
	 * @param $value
	 *
	 * @return mixed|string
	 */
	private function secureData($data, $value) : mixed {
		global $dleapi;
		$secure_arr = ['password', 'hash', 'ip', 'logged_ip'];

		if ($dleapi['secure']) {
			if (in_array($data, $secure_arr)) {
				if ($data == 'password') $value = 'Тут должен быть пароль';
				if ($data == 'hash') $value = 'Тут должен быть хэш';
				if (in_array($data, ['ip', 'logged_ip'])) $value = '127.0.0.1';
			}
		}
		return $value;

	}
}
