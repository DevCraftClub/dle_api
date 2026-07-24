<?php

declare(strict_types=1);

namespace DleApi\Xfield;

/**
 * Спецификация типов доп. полей (эталон engine/inc/xfields.php).
 */
final class XfieldTypeSpec {
	/** @var list<string> */
	public const POST_TYPES = [
		'text', 'textarea', 'htmljs', 'select', 'image', 'imagegalery',
		'video', 'audio', 'file', 'yesorno', 'datetime',
	];

	/** @var list<string> */
	public const USER_TYPES = [
		'text', 'textarea', 'select', 'yesorno', 'datetime',
	];

	/** @var list<string> ключи, которые DLE всегда пишет в JSON */
	public const COMMON_KEYS = [
		'name', 'description', 'hint', 'group', 'category',
		'allow_add_usergroups', 'allow_view_usergroups', 'type',
		'min', 'max', 'storage', 'max_files', 'max_size',
		'image_sizes', 'image_size', 'image_side', 'image_max_size',
		'thumb_size', 'thumb_side', 'max_images', 'files_ext', 'file_max_size',
		'condition', 'date_format', 'date_view_format', 'select_separator',
		'links_separator', 'not_required', 'default', 'allow_multi',
		'use_as_links', 'use_editor', 'safe_mode', 'make_watermark',
		'make_thumb', 'use_opengraph', 'is_public', 'date_local',
		'date_declension', 'allow_in_news', 'lazy_load',
	];

	/**
	 * Ключи, осмысленные для типа (projection as*()->get()).
	 *
	 * @return list<string>
	 */
	public static function allowedKeys(string $type, string $scope = 'post'): array {
		$type = strtolower($type);
		$common = ['name', 'description', 'hint', 'type', 'not_required'];
		if($scope === 'post') {
			$common = array_merge($common, [
				'group', 'category', 'allow_add_usergroups', 'allow_view_usergroups', 'allow_in_news',
			]);
		} else {
			$common = array_merge($common, [
				'registration', 'allow_change', 'private', 'safe_mode', 'condition',
			]);
		}

		$extra = match ($type) {
			'text' => ['default', 'min', 'max', 'safe_mode', 'use_as_links', 'links_separator'],
			'textarea' => ['default', 'min', 'max', 'safe_mode', 'use_editor', 'lazy_load'],
			'htmljs' => ['default'],
			'select' => ['default', 'allow_multi', 'select_separator', 'use_as_links', 'links_separator'],
			'image' => [
				'storage', 'image_sizes', 'image_size', 'image_side', 'image_max_size',
				'thumb_size', 'thumb_side', 'make_thumb', 'make_watermark', 'use_opengraph', 'lazy_load',
			],
			'imagegalery' => [
				'storage', 'image_sizes', 'image_size', 'image_side', 'image_max_size',
				'thumb_size', 'thumb_side', 'max_images', 'make_thumb', 'make_watermark',
				'use_opengraph', 'lazy_load',
			],
			'video', 'audio' => ['storage', 'max_files', 'max_size'],
			'file' => ['storage', 'files_ext', 'file_max_size', 'is_public'],
			'yesorno' => ['condition'],
			'datetime' => [
				'date_format', 'date_view_format', 'date_local', 'date_declension',
				'use_as_links', 'links_separator',
			],
			default => [],
		};

		return array_values(array_unique(array_merge($common, $extra)));
	}

	/**
	 * @return list<string>
	 */
	public static function types(string $scope = 'post'): array {
		return $scope === 'user' ? self::USER_TYPES : self::POST_TYPES;
	}

	public static function isValidType(string $type, string $scope = 'post'): bool {
		return in_array(strtolower($type), self::types($scope), true);
	}

	/**
	 * Полный шаблон поля со всеми COMMON_KEYS (как DLE save).
	 *
	 * @return array<string, mixed>
	 */
	public static function emptyDefinition(string $type = 'text'): array {
		$out = [];
		foreach(self::COMMON_KEYS as $key) {
			$out[$key] = match ($key) {
				'type' => $type,
				'not_required' => 1,
				'allow_multi', 'use_as_links', 'use_editor', 'safe_mode',
				'make_watermark', 'make_thumb', 'allow_in_news' => 0,
				default => '',
			};
		}

		return $out;
	}

	/**
	 * Projection: только allowedKeys для типа.
	 *
	 * @param array<string, mixed> $def
	 * @return array<string, mixed>
	 */
	public static function project(array $def, string $type, string $scope = 'post'): array {
		$allowed = array_flip(self::allowedKeys($type, $scope));
		$out     = [];
		foreach($def as $k => $v) {
			if(isset($allowed[$k])) {
				$out[$k] = $v;
			}
		}

		return $out;
	}
}
