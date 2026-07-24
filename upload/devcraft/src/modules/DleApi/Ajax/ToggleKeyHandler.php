<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Ajax;

use DevCraft\Core\Application;
use DevCraft\Core\Http\AjaxRequest;
use DevCraft\Core\Http\JsonResponse;
use DevCraft\Core\Interfaces\ResponseInterface;
use DevCraft\Core\Interfaces\AjaxHandlerInterface;
use DevCraft\Modules\DleApi\Models\ApiKey;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRepository;

/**
 * Включение / отключение API-ключа.
 */
final class ToggleKeyHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		$id     = (int) ($request->data['id'] ?? 0);
		$active = (bool) ($request->data['active'] ?? false);

		if($id < 1) {
			return JsonResponse::fail(__('Ошибка'), __('Некорректный идентификатор'), 'validation', 422);
		}

		/** @var ApiKeyRepository $repo */
		$repo = Application::instance()->database()->repository(ApiKey::class);
		$repo->setActive($id, $active);

		return JsonResponse::toast($active ? __('Ключ включён') : __('Ключ отключён'), [
			'id'     => $id,
			'active' => $active,
		]);
	}

}
