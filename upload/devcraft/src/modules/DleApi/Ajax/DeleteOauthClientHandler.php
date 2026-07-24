<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Ajax;

use DevCraft\Core\Application;
use DevCraft\Core\Http\AjaxRequest;
use DevCraft\Core\Http\JsonResponse;
use DevCraft\Core\Interfaces\ResponseInterface;
use DevCraft\Core\Interfaces\AjaxHandlerInterface;
use DevCraft\Modules\DleApi\Models\OauthClient;
use DevCraft\Modules\DleApi\Repositories\OauthClientRepository;

/**
 * Удаление OAuth-клиента.
 */
final class DeleteOauthClientHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		$id = (int) ($request->data['id'] ?? 0);

		if($id < 1) {
			return JsonResponse::fail(__('Ошибка'), __('Некорректный идентификатор'), 'validation', 422);
		}

		/** @var OauthClientRepository $repo */
		$repo = Application::instance()->database()->repository(OauthClient::class);
		$repo->delete($id);

		return JsonResponse::toast(__('Клиент удалён'), ['id' => $id]);
	}

}
