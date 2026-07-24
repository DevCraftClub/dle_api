<?php

declare(strict_types=1);

namespace DleApi\Xfield\Schema;

use OpenApi\Attributes as OA;

/**
 * Описание одного доп. поля новости (xfields.json → fields.*).
 */
#[OA\Schema(schema: 'PostXfieldField')]
final class PostXfieldField {
	#[OA\Property(property: 'name', type: 'string')]
	public string $name = '';

	#[OA\Property(property: 'description', type: 'string')]
	public string $description = '';

	#[OA\Property(property: 'hint', type: 'string')]
	public string $hint = '';

	#[OA\Property(property: 'group', type: 'string')]
	public string $group = '';

	#[OA\Property(property: 'category', type: 'string')]
	public string $category = '';

	#[OA\Property(property: 'allow_add_usergroups', type: 'string')]
	public string $allow_add_usergroups = '';

	#[OA\Property(property: 'allow_view_usergroups', type: 'string')]
	public string $allow_view_usergroups = '';

	#[OA\Property(
		property: 'type',
		type: 'string',
		enum: ['text', 'textarea', 'htmljs', 'select', 'image', 'imagegalery', 'video', 'audio', 'file', 'yesorno', 'datetime'],
	)]
	public string $type = 'text';

	#[OA\Property(property: 'min', type: 'string')]
	public string $min = '';

	#[OA\Property(property: 'max', type: 'string')]
	public string $max = '';

	#[OA\Property(property: 'storage', type: 'string')]
	public string $storage = '';

	#[OA\Property(property: 'max_files', type: 'string')]
	public string $max_files = '';

	#[OA\Property(property: 'max_size', type: 'string')]
	public string $max_size = '';

	#[OA\Property(property: 'image_sizes', type: 'string')]
	public string $image_sizes = '';

	#[OA\Property(property: 'image_size', type: 'string')]
	public string $image_size = '';

	#[OA\Property(property: 'image_side', type: 'string')]
	public string $image_side = '';

	#[OA\Property(property: 'image_max_size', type: 'string')]
	public string $image_max_size = '';

	#[OA\Property(property: 'thumb_size', type: 'string')]
	public string $thumb_size = '';

	#[OA\Property(property: 'thumb_side', type: 'string')]
	public string $thumb_side = '';

	#[OA\Property(property: 'max_images', type: 'string')]
	public string $max_images = '';

	#[OA\Property(property: 'files_ext', type: 'string')]
	public string $files_ext = '';

	#[OA\Property(property: 'file_max_size', type: 'string')]
	public string $file_max_size = '';

	#[OA\Property(property: 'condition', type: 'string')]
	public string $condition = '';

	#[OA\Property(property: 'date_format', type: 'string')]
	public string $date_format = '';

	#[OA\Property(property: 'date_view_format', type: 'string')]
	public string $date_view_format = '';

	#[OA\Property(property: 'select_separator', type: 'string')]
	public string $select_separator = '';

	#[OA\Property(property: 'links_separator', type: 'string')]
	public string $links_separator = '';

	#[OA\Property(property: 'not_required', type: 'integer')]
	public int $not_required = 1;

	#[OA\Property(property: 'default', type: 'string', description: 'Для select — варианты через \\n')]
	public string $default = '';

	#[OA\Property(property: 'allow_multi', type: 'integer')]
	public int $allow_multi = 0;

	#[OA\Property(property: 'use_as_links', type: 'integer')]
	public int $use_as_links = 0;

	#[OA\Property(property: 'use_editor', type: 'integer')]
	public int $use_editor = 0;

	#[OA\Property(property: 'safe_mode', type: 'integer')]
	public int $safe_mode = 0;

	#[OA\Property(property: 'make_watermark', type: 'integer')]
	public int $make_watermark = 0;

	#[OA\Property(property: 'make_thumb', type: 'integer')]
	public int $make_thumb = 0;

	#[OA\Property(property: 'use_opengraph', type: 'string')]
	public string $use_opengraph = '';

	#[OA\Property(property: 'is_public', type: 'string')]
	public string $is_public = '';

	#[OA\Property(property: 'date_local', type: 'string')]
	public string $date_local = '';

	#[OA\Property(property: 'date_declension', type: 'string')]
	public string $date_declension = '';

	#[OA\Property(property: 'allow_in_news', type: 'integer')]
	public int $allow_in_news = 0;

	#[OA\Property(property: 'lazy_load', type: 'string')]
	public string $lazy_load = '';
}
