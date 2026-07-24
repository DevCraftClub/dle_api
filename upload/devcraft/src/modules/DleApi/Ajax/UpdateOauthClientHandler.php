<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Ajax;

use DevCraft\Core\Application;
use DevCraft\Core\Http\AjaxRequest;
use DevCraft\Core\Http\JsonResponse;
use DevCraft\Core\Interfaces\ResponseInterface;
use DevCraft\Core\Interfaces\AjaxHandlerInterface;
use DevCraft\Modules\DleApi\Models\ApiKey;
use DevCraft\Modules\DleApi\Models\OauthClient;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRepository;
use DevCraft\Modules\DleApi\Repositories\OauthClientRepository;
use DevCraft\Modules\DleApi\Services\OauthGrantTypes;
use DevCraft\Modules\DleApi\Services\OauthRedirectUri;

/**
 * Обновление OAuth-клиента (имя, API-ключ, redirect_uri, grant_types, active).
 */
final class UpdateOauthClientHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		$id         = (int) ($request->data['id'] ?? 0);
		$apiKeyId   = (int) ($request->data['api_key_id'] ?? 0);
		$name       = trim((string) ($request->data['name'] ?? ''));
		$redirect   = OauthRedirectUri::normalize((string) ($request->data['redirect_uri'] ?? ''));
		$active     = !empty($request->data['active']);
		$grantTypes = OauthGrantTypes::normalize($request->data['grant_types'] ?? '');

		if($id < 1) {
			return JsonResponse::fail(__('Ошибка'), __('Некорректный идентификатор'), 'validation', 422);
		}

		if($redirect === false) {
			return JsonResponse::fail(__('Ошибка'), __('Redirect URI должен быть ссылкой http(s)'), 'validation', 422);
		}

		if($grantTypes === '') {
			return JsonResponse::fail(__('Ошибка'), __('Выберите хотя бы один grant_type'), 'validation', 422);
		}

		/** @var ApiKeyRepository $keys */
		$keys = Application::instance()->database()->repository(ApiKey::class);
		$key  = $apiKeyId > 0 ? $keys->find($apiKeyId) : null;

		if($apiKeyId < 1 || !($key instanceof ApiKey)) {
			return JsonResponse::fail(__('Ошибка'), __('Укажите действующий API-ключ'), 'validation', 422);
		}

		/** @var OauthClientRepository $clients */
		$clients = Application::instance()->database()->repository(OauthClient::class);
		$entity  = $clients->find($id);

		if($entity === null) {
			return JsonResponse::fail(__('Ошибка'), __('Клиент не найден'), 'not_found', 404);
		}

		$updated = $clients->update($entity, [
			'name'         => $name !== '' ? $name : $entity->client_id,
			'redirect_uri' => $redirect ?? '',
			'api_key_id'   => $apiKeyId,
			'grant_types'  => $grantTypes,
			'active'       => $active,
		]);

		return JsonResponse::toast(__('Клиент сохранён'), [
			'id'              => $updated->id(),
			'name'            => $updated->name,
			'client_id'       => $updated->client_id,
			'api_key_id'      => $updated->api_key_id,
			'api_key_preview' => $key->api,
			'redirect_uri'    => (string) ($updated->redirect_uri ?? ''),
			'grant_types'     => $updated->grant_types,
			'active'          => $updated->active,
		]);
	}

}
