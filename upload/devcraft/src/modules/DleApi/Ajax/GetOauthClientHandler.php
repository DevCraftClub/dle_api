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
 * Данные OAuth-клиента для формы редактирования.
 */
final class GetOauthClientHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		$id = (int) ($request->data['id'] ?? 0);

		if($id < 1) {
			return JsonResponse::fail(__('Ошибка'), __('Некорректный идентификатор'), 'validation', 422);
		}

		/** @var OauthClientRepository $repo */
		$repo   = Application::instance()->database()->repository(OauthClient::class);
		$client = $repo->find($id);

		if($client === null) {
			return JsonResponse::fail(__('Ошибка'), __('Клиент не найден'), 'not_found', 404);
		}

		return JsonResponse::ok([
			'id'           => $client->id(),
			'name'         => $client->name,
			'client_id'    => $client->client_id,
			'api_key_id'   => $client->api_key_id,
			'redirect_uri' => (string) ($client->redirect_uri ?? ''),
			'grant_types'  => $client->grant_types,
			'active'       => $client->active,
		]);
	}

}
