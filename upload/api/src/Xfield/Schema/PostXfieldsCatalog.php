<?php

declare(strict_types=1);

namespace DleApi\Xfield\Schema;

use OpenApi\Attributes as OA;

/**
 * Каталог доп. полей новостей (xfields.json).
 */
#[OA\Schema(schema: 'PostXfieldsCatalog')]
final class PostXfieldsCatalog {
	/**
	 * @var array<string, PostXfieldField>
	 */
	#[OA\Property(
		property: 'fields',
		type: 'object',
		description: 'Карта полей: имя → определение',
		additionalProperties: new OA\AdditionalProperties(ref: PostXfieldField::class),
	)]
	public array $fields = [];

	/**
	 * @var array<string, PostXfieldGroup>
	 */
	#[OA\Property(
		property: 'groups',
		type: 'object',
		description: 'Карта групп: имя → определение',
		additionalProperties: new OA\AdditionalProperties(ref: PostXfieldGroup::class),
	)]
	public array $groups = [];
}
