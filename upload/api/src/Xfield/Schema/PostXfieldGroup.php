<?php

declare(strict_types=1);

namespace DleApi\Xfield\Schema;

use OpenApi\Attributes as OA;

/**
 * Группа доп. полей новости (xfields.json → groups.*).
 */
#[OA\Schema(schema: 'PostXfieldGroup')]
final class PostXfieldGroup {
	#[OA\Property(property: 'title', type: 'string', description: 'Заголовок группы')]
	public string $title = '';

	#[OA\Property(property: 'description', type: 'string', description: 'Описание группы')]
	public string $description = '';
}
