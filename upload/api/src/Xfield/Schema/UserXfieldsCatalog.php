<?php

declare(strict_types=1);

namespace DleApi\Xfield\Schema;

use OpenApi\Attributes as OA;

/**
 * Каталог доп. полей профиля (userxfields.json).
 */
#[OA\Schema(schema: 'UserXfieldsCatalog')]
final class UserXfieldsCatalog {
	/**
	 * @var array<string, UserXfieldField>
	 */
	#[OA\Property(
		property: 'fields',
		type: 'object',
		description: 'Карта полей: имя → определение',
		additionalProperties: new OA\AdditionalProperties(ref: UserXfieldField::class),
	)]
	public array $fields = [];
}
