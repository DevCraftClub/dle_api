<?php

declare(strict_types=1);

namespace DleApi\Xfield;

/**
 * Сериализация значений в формат dle_post.xfields / users.xfields (DLEXFields::Parse).
 */
final class XfieldValueEncoder {
	/**
	 * @param array<string, mixed> $map имя → значение
	 * @param array<string, string> $types имя → type (для prepareValue)
	 */
	public static function encode(array $map, array $types = []): string {
		$parts = [];
		foreach($map as $name => $value) {
			$type = $types[(string) $name] ?? 'text';
			$pair = self::encodePair((string) $name, self::prepareValue($type, $value));
			if($pair !== '') {
				$parts[] = $pair;
			}
		}

		return implode('||', $parts);
	}

	public static function encodePair(string $name, string $value): string {
		if($value === '') {
			return '';
		}
		$name  = self::escapeToken($name);
		$value = self::escapeToken($value);

		return $name . '|' . $value;
	}

	public static function prepareValue(string $type, mixed $value): string {
		$type = strtolower($type);
		if($type === 'yesorno') {
			return (string) ((int) (bool) $value);
		}
		if(is_array($value)) {
			$value = implode(',', array_map('strval', $value));
		}
		$str = (string) $value;
		if($type === 'datetime' && $str !== '') {
			$str = str_replace(':', '&#58;', $str);
		}

		return $str;
	}

	public static function escapeToken(string $token): string {
		$token = str_replace('|', '&#124;', $token);
		$token = str_replace("\r", '', $token);
		$token = str_replace("\n", '__NEWL__', $token);

		return $token;
	}

	/**
	 * Encode map с типами из каталога; неизвестные имена → exception.
	 *
	 * @param array<string, mixed> $map
	 * @return array{raw: string, parsed: array<string, string>}
	 */
	public static function encodeFromStore(XfieldStore $store, array $map): array {
		$types  = [];
		$errors = [];
		foreach($map as $name => $_) {
			$name = (string) $name;
			$def  = $store->getField($name);
			if($def === null) {
				$errors[$name] = 'Поле не найдено в каталоге';
				continue;
			}
			$types[$name] = (string) ($def['type'] ?? 'text');
		}
		if($errors !== []) {
			throw new XfieldValidationException('Неизвестные доп. поля', $errors);
		}
		$raw = self::encode($map, $types);

		return [
			'raw'    => $raw,
			'parsed' => function_exists('dle_api_parse_xfields')
				? dle_api_parse_xfields($raw)
				: self::decodeSimple($raw),
		];
	}

	/**
	 * @return array<string, string>
	 */
	private static function decodeSimple(string $raw): array {
		$out = [];
		if($raw === '') {
			return $out;
		}
		foreach(explode('||', $raw) as $chunk) {
			if(!str_contains($chunk, '|')) {
				continue;
			}
			[$n, $v] = explode('|', $chunk, 2);
			$n       = str_replace(['&#124;', '__NEWL__'], ['|', "\n"], $n);
			$v       = str_replace(['&#124;', '__NEWL__'], ['|', "\n"], $v);
			$out[$n] = $v;
		}

		return $out;
	}
}
