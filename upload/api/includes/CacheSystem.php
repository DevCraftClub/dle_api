<?php


/**
 * Система кеширования запросов
 */
class CacheSystem {
	private string $cachePath;
	private string $module;
	private string $id;
	private mixed  $data;
	private string $app;

	/**
	 * CacheSystem constructor.
	 *
	 * @param              $module // Название таблицы
	 * @param string       $id     // Идентификационный набор символов
	 * @param mixed        $data   // Передаваемые и сохраняемые данные
	 * @param string       $app    // Тип кеша
	 * @param string       $path   // Путь кеша
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
	public function setCachePath(string $cachePath): void {
		if (!mkdir($cachePath) && !is_dir($cachePath)) {
			throw new \RuntimeException(sprintf('Directory "%s" was not created', $cachePath));
		}
		$this->cachePath = $cachePath;
	}

	/**
	 * @return bool|string
	 */
	public function create(): bool|string {
		$file_name = "{$this->app}_{$this->module}_" . md5($this->id) . '.json';
		file_put_contents(
			$this->cachePath . '/' . $file_name,
			json_encode($this->data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
		);

		return $this->get();
	}

	/**
	 * Возвращает сохранённый в кеше запрос
	 *
	 * @return string
	 */
	public function get(): string {
		$file_name  = "{$this->app}_{$this->module}_" . md5($this->id) . '.json';
		$cache_file = $this->cachePath . DIRECTORY_SEPARATOR . $file_name;
		if (file_exists($cache_file)) {
			$return_data = json_decode(file_get_contents($cache_file), true);
			foreach ($return_data as $id => $data) {
				foreach ($data as $key => $value)
					$return_data[$id][$key] = $this->secureData($key, $value);
			}
			return json_encode($return_data, JSON_UNESCAPED_UNICODE);
		}

		return json_encode([], JSON_UNESCAPED_UNICODE);
	}

	/**
	 * Очищает файлы кеша
	 *
	 * @param string $app
	 */
	public function clear(string $app = ''): void {
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
	public function setData(mixed $data): void {
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
	private function secureData($data, $value): mixed {
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