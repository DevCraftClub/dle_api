<?php

declare(strict_types=1);

namespace DleApi\Xfield;

/**
 * Нормализация и валидация определения поля каталога.
 */
final class XfieldDefinitionNormalizer {
	public function __construct(private XfieldStore $store) {}

	/**
	 * @param array<string, mixed> $def
	 * @return array<string, mixed>
	 */
	public function normalize(array $def, bool $requireUniqueName = false): array {
		$errors = [];
		$scope  = $this->store->scope();

		$name = isset($def['name']) ? trim((string) $def['name']) : '';
		$name = self::toTranslitCompat($name);
		if($name === '') {
			$errors['name'] = 'Имя поля обязательно';
		} elseif(!preg_match('/^[a-z0-9_]{1,30}$/', $name)) {
			$errors['name'] = 'Имя: латиница, цифры, _ (до 30 символов)';
		}

		$description = isset($def['description']) ? trim((string) $def['description']) : '';
		if($description === '') {
			$errors['description'] = 'Описание (description) обязательно';
		}

		$type = isset($def['type']) ? strtolower(trim((string) $def['type'])) : '';
		if($type === '' || !XfieldTypeSpec::isValidType($type, $scope)) {
			$errors['type'] = 'Недопустимый тип поля для scope=' . $scope;
		}

		if($requireUniqueName && $name !== '' && $this->store->getField($name) !== null) {
			$errors['name'] = 'Поле с таким именем уже существует';
		}

		$group = isset($def['group']) ? trim((string) $def['group']) : '';
		if($scope === 'post' && $group !== '' && $this->store->getGroup($group) === null) {
			$errors['group'] = 'Группа не найдена в каталоге';
		}

		if($type === 'select') {
			$opts = self::parseSelectDefault($def['default'] ?? '');
			if(count($opts) < 2) {
				$errors['default'] = 'Для select нужно не менее 2 вариантов в default';
			}
		}

		if($errors !== []) {
			throw new XfieldValidationException('Ошибка валидации доп. поля', $errors);
		}

		$out = XfieldTypeSpec::emptyDefinition($type);
		$allowed = array_flip(XfieldTypeSpec::allowedKeys($type, $scope));
		// Пишем все COMMON_KEYS; значения из def только для allowed + common always-present
		foreach(XfieldTypeSpec::COMMON_KEYS as $key) {
			if($key === 'name') {
				$out[$key] = $name;
				continue;
			}
			if($key === 'description') {
				$out[$key] = $description;
				continue;
			}
			if($key === 'type') {
				$out[$key] = $type;
				continue;
			}
			if(!isset($allowed[$key])) {
				// чужому типу — пустое/0 из emptyDefinition
				continue;
			}
			if(array_key_exists($key, $def)) {
				$out[$key] = self::castValue($key, $def[$key]);
			}
		}

		// user-only keys
		if($scope === 'user') {
			foreach(['registration', 'allow_change', 'private'] as $uk) {
				if(array_key_exists($uk, $def)) {
					$out[$uk] = (int) (bool) $def[$uk];
				} elseif(!isset($out[$uk])) {
					$out[$uk] = $uk === 'allow_change' ? 1 : 0;
				}
			}
		}

		if($type === 'select' && is_array($def['default'] ?? null)) {
			$out['default'] = implode("\n", array_map('strval', $def['default']));
		}

		return $out;
	}

	/**
	 * @return list<string>
	 */
	private static function parseSelectDefault(mixed $default): array {
		if(is_array($default)) {
			$lines = $default;
		} else {
			$lines = preg_split("/\r\n|\n|\r/", (string) $default) ?: [];
		}
		$out = [];
		foreach($lines as $line) {
			$line = trim((string) $line);
			if($line === '') {
				continue;
			}
			$out[$line] = $line;
		}

		return array_values($out);
	}

	private static function castValue(string $key, mixed $value): mixed {
		$boolKeys = [
			'not_required', 'allow_multi', 'use_as_links', 'use_editor', 'safe_mode',
			'make_watermark', 'make_thumb', 'allow_in_news', 'date_local', 'date_declension',
			'lazy_load', 'is_public', 'use_opengraph', 'registration', 'allow_change', 'private',
		];
		if(in_array($key, $boolKeys, true)) {
			return (int) (bool) $value;
		}
		if(is_array($value)) {
			return implode(',', array_map('strval', $value));
		}

		return $value;
	}

	/** Упрощённый totranslit: только [a-z0-9_]. */
	private static function toTranslitCompat(string $name): string {
		$name = strtolower(trim($name));
		$name = preg_replace('/[^a-z0-9_]/', '', $name) ?? '';

		return $name;
	}
}
