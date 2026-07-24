<?php

declare(strict_types=1);

namespace DleApi\Xfield\Schema;

use OpenApi\Attributes as OA;

/**
 * Описание доп. поля профиля (userxfields.json → fields.*).
 */
#[OA\Schema(schema: 'UserXfieldField')]
final class UserXfieldField {
	#[OA\Property(property: 'name', type: 'string', description: 'Системное имя поля')]
	public string $name = '';

	#[OA\Property(property: 'description', type: 'string', description: 'Заголовок поля')]
	public string $description = '';

	#[OA\Property(property: 'type', type: 'string', description: 'Тип поля')]
	public string $type = 'text';

	#[OA\Property(property: 'condition', type: 'string', description: 'Условие отображения')]
	public string $condition = '0';

	#[OA\Property(property: 'safe_mode', type: 'integer', description: 'Безопасный режим (0/1)')]
	public int $safe_mode = 1;

	#[OA\Property(property: 'registration', type: 'integer', description: 'Показывать при регистрации (0/1)')]
	public int $registration = 0;

	#[OA\Property(property: 'allow_change', type: 'integer', description: 'Разрешить изменение (0/1)')]
	public int $allow_change = 1;

	#[OA\Property(property: 'private', type: 'integer', description: 'Приватное поле (0/1)')]
	public int $private = 0;
}
