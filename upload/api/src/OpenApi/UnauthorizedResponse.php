<?php

declare(strict_types=1);

namespace DleApi\OpenApi;

use OpenApi\Attributes as OA;

/**
 * Переиспользуемый ответ 401 (Bearer).
 */
#[OA\Response(
	response: 'Unauthorized',
	description: 'Нет Authorization: Bearer или AuthToken недействителен/просрочен',
	content: new OA\JsonContent(
		ref: ApiError::class,
		examples: [
			'missing' => new OA\Examples(
				example: 'missing',
				summary: 'Нет Bearer',
				value: [
					'error'   => 'unauthorized',
					'message' => 'Требуется Authorization: Bearer <AuthToken>',
				],
			),
			'invalid' => new OA\Examples(
				example: 'invalid',
				summary: 'Неверный или просроченный token',
				value: [
					'error'   => 'unauthorized',
					'message' => 'Недействительный или просроченный AuthToken',
				],
			),
		],
	),
)]
final class UnauthorizedResponse {
}
