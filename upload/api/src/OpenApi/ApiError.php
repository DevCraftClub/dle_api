<?php

declare(strict_types=1);

namespace DleApi\OpenApi;

use OpenApi\Attributes as OA;

/**
 * Единый JSON-формат ошибки API v2: error, message, details?
 */
#[OA\Schema(
	schema: 'ApiError',
	description: 'Единый JSON-формат ошибки API v2: error, message, details?',
	required: ['error', 'message'],
	properties: [
		new OA\Property(property: 'error', type: 'string', description: 'Код ошибки', example: 'unauthorized'),
		new OA\Property(property: 'message', type: 'string', description: 'Человекочитаемое сообщение', example: 'Требуется Authorization: Bearer <AuthToken>'),
		new OA\Property(
			property: 'details',
			type: 'object',
			nullable: true,
			additionalProperties: true,
			description: 'Доп. детали (validation и т.п.)',
		),
	],
	type: 'object',
	example: [
		'error'   => 'unauthorized',
		'message' => 'Требуется Authorization: Bearer <AuthToken>',
	],
)]
final class ApiError {
}
