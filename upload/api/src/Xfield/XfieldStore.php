<?php

declare(strict_types=1);

namespace DleApi\Xfield;

/**
 * Каталог доп. полей: post → xfields.json, user → userxfields.json.
 */
final class XfieldStore {
	private string $scope;

	private string $path;

	public function __construct(string $scope = 'post') {
		$scope = strtolower($scope);
		if($scope !== 'post' && $scope !== 'user') {
			throw new \InvalidArgumentException('scope должен быть post или user');
		}
		$this->scope = $scope;
		$file        = $scope === 'user' ? 'userxfields.json' : 'xfields.json';
		if(!defined('ENGINE_DIR')) {
			throw new \RuntimeException('ENGINE_DIR не определён');
		}
		$this->path = ENGINE_DIR . '/data/' . $file;
	}

	public function scope(): string {
		return $this->scope;
	}

	/** @return array{fields?: array<string, array<string, mixed>>, groups?: array<string, array<string, mixed>>} */
	public function read(): array {
		if(!is_file($this->path)) {
			return ['fields' => []];
		}
		$data = json_decode((string) file_get_contents($this->path), true);

		return is_array($data) ? $data : ['fields' => []];
	}

	/**
	 * @param array{fields?: array<string, array<string, mixed>>, groups?: array<string, array<string, mixed>>} $catalog
	 */
	public function write(array $catalog): void {
		$dir = dirname($this->path);
		if(!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}
		$json = json_encode($catalog, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		if($json === false) {
			throw new \RuntimeException('Не удалось сериализовать xfields');
		}
		$tmp = $this->path . '.tmp.' . bin2hex(random_bytes(4));
		if(file_put_contents($tmp, $json . "\n", LOCK_EX) === false) {
			throw new \RuntimeException('Не удалось записать временный файл xfields');
		}
		if(!rename($tmp, $this->path)) {
			@unlink($tmp);
			throw new \RuntimeException('Не удалось заменить xfields.json');
		}
		if($this->scope === 'user') {
			$cache = ENGINE_DIR . '/cache/system/userxfields.php';
			if(is_file($cache)) {
				@unlink($cache);
			}
		}
	}

	/** @return array<string, mixed>|null */
	public function getField(string $name): ?array {
		$fields = $this->read()['fields'] ?? [];

		return isset($fields[$name]) && is_array($fields[$name]) ? $fields[$name] : null;
	}

	/** @param array<string, mixed> $definition */
	public function upsertField(string $name, array $definition): void {
		$catalog = $this->read();
		if(!isset($catalog['fields']) || !is_array($catalog['fields'])) {
			$catalog['fields'] = [];
		}
		$definition['name']         = $name;
		$catalog['fields'][$name] = $definition;
		$this->write($catalog);
	}

	public function deleteField(string $name): void {
		$catalog = $this->read();
		unset($catalog['fields'][$name]);
		$this->write($catalog);
	}

	/** @return array<string, mixed>|null */
	public function getGroup(string $id): ?array {
		if($this->scope !== 'post') {
			return null;
		}
		$groups = $this->read()['groups'] ?? [];

		return isset($groups[$id]) && is_array($groups[$id]) ? $groups[$id] : null;
	}

	/** @param array<string, mixed> $definition */
	public function upsertGroup(string $id, array $definition): void {
		if($this->scope !== 'post') {
			throw new \RuntimeException('groups только для post xfields');
		}
		$catalog = $this->read();
		if(!isset($catalog['groups']) || !is_array($catalog['groups'])) {
			$catalog['groups'] = [];
		}
		$catalog['groups'][$id] = $definition;
		$this->write($catalog);
	}

	public function deleteGroup(string $id): void {
		if($this->scope !== 'post') {
			return;
		}
		$catalog = $this->read();
		unset($catalog['groups'][$id]);
		$this->write($catalog);
	}
}
