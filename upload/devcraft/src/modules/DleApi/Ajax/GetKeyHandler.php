<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Ajax;

use DevCraft\Core\Application;
use DevCraft\Core\Http\AjaxRequest;
use DevCraft\Core\Http\JsonResponse;
use DevCraft\Core\Interfaces\ResponseInterface;
use DevCraft\Core\Interfaces\AjaxHandlerInterface;
use DevCraft\Modules\DleApi\Models\ApiKey;
use DevCraft\Modules\DleApi\Models\ApiScope;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRepository;
use DevCraft\Modules\DleApi\Repositories\ApiScopeRepository;

/**
 * Данные ключа и его scope для формы редактирования.
 */
final class GetKeyHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		$id = (int) ($request->data['id'] ?? 0);

		if($id < 1) {
			return JsonResponse::fail(__('Ошибка'), __('Некорректный идентификатор'), 'validation', 422);
		}

		/** @var ApiKeyRepository $repo */
		$repo = Application::instance()->database()->repository(ApiKey::class);
		$key  = $repo->find($id);

		if($key === null) {
			return JsonResponse::fail(__('Ошибка'), __('Ключ не найден'), 'not_found', 404);
		}

		/** @var ApiScopeRepository $scopes */
		$scopes = Application::instance()->database()->repository(ApiScope::class);

		return JsonResponse::ok([
			'id'               => $key->id(),
			'api'              => $key->api,
			'is_admin'         => $key->is_admin,
			'active'           => $key->active,
			'user_id'          => $key->user_id,
			'own_only'         => $key->own_only,
			'access_level_id'  => $key->access_level_id,
			'tables'           => $scopes->mapForKey($id),
		]);
	}

}
