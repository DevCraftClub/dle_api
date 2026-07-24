<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Ajax;

use DevCraft\Core\Http\AjaxRequest;
use DevCraft\Core\Http\JsonResponse;
use DevCraft\Core\Interfaces\ResponseInterface;
use DevCraft\Core\Interfaces\AjaxHandlerInterface;
use DevCraft\Modules\DleApi\Services\ApiKeyService;

/**
 * Создание API-ключа и его scope.
 */
final class CreateKeyHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		global $member_id;

		$tables    = is_array($request->data['tables'] ?? null) ? $request->data['tables'] : [];
		$creatorId = (int) ($member_id['user_id'] ?? 0);
		$result    = (new ApiKeyService())->create($request->data, $tables, $creatorId);

		return JsonResponse::toast(__('Ключ создан'), $result);
	}

}
