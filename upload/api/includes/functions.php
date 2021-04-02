<?php
	if( !defined( 'DATALIFEENGINE' ) ) {
		header('HTTP/1.1 403 Forbidden');
		header ( 'Location: ../../' );
		die('Hacking attempt!');
	}

	include_once (DLEPlugins::Check(API_DIR . '/vendor/autoload.php'));
	include_once (DLEPlugins::Check(API_DIR . '/includes/PDO.class.php'));
	include_once (DLEPlugins::Check(ENGINE_DIR . '/data/dbconfig.php'));
	include_once DLEPlugins::Check(ENGINE_DIR.'/inc/includes/functions.inc.php');
	$dleapi = json_decode(file_get_contents(DLEPlugins::Check(ENGINE_DIR . '/data/dleapi.json')), true );

	$connect = new database(DBHOST, 3306, DBNAME, DBUSER, DBPASS);
	$DLEprefix = PREFIX;
	$USERprefix = USERPREFIX;

	function getComparer($value, $type = null) {
		$firstSign = array('!', '<', '>', '%');
		$secondSign = array('=');
		$type = gettype(defType($value, $type));
		$outSign = '=';
		$checkSign = NULL;

		if (!in_array($type, ['integer', 'double', 'boolean']) && in_array($value[0], $firstSign, true)) {
		$checkSign = $value[0];
			if (in_array($value[1], $secondSign, true)){
				$checkSign .= $value[1];
				$value = substr($value, 2);
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
			$value = '%'. $value .'%';
		}

		$value = defType($value, $type);

		return " {$outSign} {$value}";
	}

	function checkAPI ($key,  $name) {
		global $connect, $DLEprefix, $USERprefix;

		$antwort = array(
			'admin' => false,
			'read' => false,
			'view' => false,
			'delete' => false,
		);

		try {
			if (!empty($key) && !empty($name)) {
				$keyCheck = $connect->query( "SELECT k.id, k.api, k.is_admin, k.active, u.user_id, k.own_only, u.name FROM  {$DLEprefix}_api_keys k, {$USERprefix}_users u WHERE u.user_id = k.user_id and api = :key", array( 'key' => $key) );

				if(!empty($keyCheck)) {
					if($keyCheck[0]['is_admin'] && $keyCheck[0]['active'] === 1) {
						$antwort = array(
							'admin' => true,
							'read' => true,
							'view' => true,
							'delete' => true,
							'own' => [
								'access' => true,
								'user_id' => $keyCheck[0]['user_id'],
								'user_name' => $keyCheck[0]['name']
							],
						);
					} else {

						$tablesCheck = $connect->query( "SELECT * FROM {$DLEprefix}_api_scope
														WHERE table = :name and key_id = :api", array(  'name' => $name, 'api' => $keyCheck[0]['api']) );

						if ( count( $tablesCheck ) > 0 ) {
							if ( $keyCheck[0]['active'] === 1 ) {
								if ( $keyCheck[0]['is_admin'] === 1 ) $antwort['admin'] = true;
								if ( $tablesCheck[0]['read'] === 1 ) $antwort['read'] = true;
								if ( $tablesCheck[0]['view'] === 1 ) $antwort['view'] = true;
								if ( $tablesCheck[0]['delete'] === 1 ) $antwort['delete'] = true;
								if ( $keyCheck[0]['own_only'] === 1 ) $antwort['own']['access'] = true;
								$antwort['own']['user_id'] = $keyCheck[0]['user_id'];
								$antwort['own']['user_name'] = $keyCheck[0]['name'];
							}
							else $antwort['error'] = 'API-ключ не активен!';
						}
						else $antwort['error'] = 'API-ключ не действителен!';
					}
				}
				else $antwort['error'] = 'API-ключ не действителен!';
			} else {
				if (!isset($key)) $antwort['error'] = 'API-ключ не может быть пустым!';
				if (!isset($name)) $antwort['error'] = 'Название базы данных не может быть пустым!';
			}

			return $antwort;

		} catch (Exception $e) {
			throw new Error($e->getMessage());
		}
	}

	function defType($value, $type = null) {
		$output = null;

		if ($type === 'integer') $output = (int)$value;
		elseif ($type === 'boolean') $output = (bool)$value;
		elseif ($type === 'double') $output = (float)$value;
		else $output = "'{$value}'";

		return $output;
	}

	function checkLength($text, $max) {
		return (strlen($text) > $max && $max !== 0) ? substr($text, 0, $max) : $text;
	}

	class CacheSystem {
		private $app, $data, $module, $id, $cachePath;

		/**
		 * CacheSystem constructor.
		 *
		 * @param        $module // Название таблицы
		 * @param string $id     // Идентификационный набор символов
		 * @param string $data   // Передаваемые и сохраняемые данные
		 * @param string $app    // Тип кеша
		 * @param string $path   // Путь кеша
		 */
		public function __construct ($module, $id = '', $data = '', $app = 'full', $path = ENGINE_DIR . '/cache') {
			$this->data = $data;
			$this->app = $app;
			$this->module = $module;
			$this->id = $id;
			if (empty($this->id)) $this->id = "{$app}_{$module}";
			$this->setCachePath($path);
		}

		/**
		 * @param string $cachePath
		 */
		public function setCachePath ($cachePath): void {
			if (!mkdir($cachePath) && !is_dir($cachePath)) {
				throw new \RuntimeException(sprintf('Directory "%s" was not created', $cachePath));
			}
			$this->cachePath = $cachePath;
		}

		/**
		 * @return string
		 */
		public function create() {
			$file_name = "{$this->app}_{$this->module}_" . md5($this->id) .'.json';
			file_put_contents($this->cachePath .'/'. $file_name, $this->data);

			return $this->get();
		}

		/**
		 * @return bool|false|string
		 */
		public function get() {
			$file_name = "{$this->app}_{$this->module}_" . md5($this->id) .'.json';
			if (file_exists($this->cachePath .'/'. $file_name)) {
				$return_data = json_decode(file_get_contents($this->cachePath.'/'.$file_name), true);
				foreach ($return_data as $id => $data) {
					foreach ($data as $key => $value)
						$return_data[$id][$key] = $this->secureData($key, $value);
				}
				return json_encode($return_data);
			}

			return false;
		}

		/**
		 * @param string $app
		 */
		public function clear($app = ''): void {
			$pattern = (empty($app)) ? '*' : $this->app .'_'. $app . '_*';
			$pattern .= '.json';
			try {
				foreach (glob($this->cachePath .'/'.$pattern) as $filename) {
					unlink($filename);
				}
			} catch (Exception $e) {
				throw new Error($e->getMessage());
			}
		}

		/**
		 * @param mixed $data
		 */
		public function setData ($data) {
			$this->data = $data;
		}

		/**
		 * @param $data
		 * @param $value
		 *
		 * @return mixed|string
		 */
		private function secureData($data, $value): string {
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