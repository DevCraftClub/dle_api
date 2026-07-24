<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Ajax;

use DevCraft\Core\Http\AjaxRequest;
use DevCraft\Core\Http\JsonResponse;
use DevCraft\Core\Interfaces\ResponseInterface;
use DevCraft\Core\Interfaces\AjaxHandlerInterface;
use DevCraft\Modules\DleApi\Services\ApiKeyService;
use RuntimeException;

/**
 * Обновление API-ключа и матрицы scope.
 */
final class UpdateKeyHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		$id     = (int) ($request->data['id'] ?? 0);
		$tables = is_array($request->data['tables'] ?? null) ? $request->data['tables'] : [];

		try {
			$result = (new ApiKeyService())->update($id, $request->data, $tables);
		} catch(RuntimeException $e) {
			if($e->getMessage() === 'not_found') {
				return JsonResponse::fail(__('Ошибка'), __('Ключ не найден'), 'not_found', 404);
			}

			return JsonResponse::fail(__('Ошибка'), __('Некорректный идентификатор'), 'validation', 422);
		}

		return JsonResponse::toast(__('Ключ сохранён'), $result);
	}

}
