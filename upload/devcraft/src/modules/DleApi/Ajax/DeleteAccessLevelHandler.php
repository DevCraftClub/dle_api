<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Ajax;

use DevCraft\Core\Application;
use DevCraft\Core\Http\AjaxRequest;
use DevCraft\Core\Http\JsonResponse;
use DevCraft\Core\Interfaces\AjaxHandlerInterface;
use DevCraft\Core\Interfaces\ResponseInterface;
use DevCraft\Modules\DleApi\Models\ApiAccessLevel;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelRepository;

/**
 * Удаление уровня доступа.
 */
final class DeleteAccessLevelHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		$id = (int) ($request->data['id'] ?? 0);
		if($id < 1) {
			return JsonResponse::fail(__('Ошибка'), __('Некорректный идентификатор'), 'validation', 422);
		}

		/** @var ApiAccessLevelRepository $repo */
		$repo = Application::instance()->database()->repository(ApiAccessLevel::class);
		$repo->delete($id);

		return JsonResponse::toast(__('Удалено'), ['id' => $id]);
	}

}
