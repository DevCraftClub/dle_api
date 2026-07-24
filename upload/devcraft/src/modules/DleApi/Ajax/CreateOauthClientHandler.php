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
 * Создание OAuth-клиента, привязанного к API-ключу.
 */
final class CreateOauthClientHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		$apiKeyId   = (int) ($request->data['api_key_id'] ?? 0);
		$name       = trim((string) ($request->data['name'] ?? ''));
		$redirect   = OauthRedirectUri::normalize((string) ($request->data['redirect_uri'] ?? ''));
		$grantTypes = OauthGrantTypes::normalize($request->data['grant_types'] ?? OauthGrantTypes::DEFAULT);

		if($redirect === false) {
			return JsonResponse::fail(__('Ошибка'), __('Redirect URI должен быть ссылкой http(s)'), 'validation', 422);
		}

		if($grantTypes === '') {
			return JsonResponse::fail(__('Ошибка'), __('Выберите хотя бы один grant_type'), 'validation', 422);
		}

		/** @var ApiKeyRepository $keys */
		$keys = Application::instance()->database()->repository(ApiKey::class);

		$key = $apiKeyId > 0 ? $keys->find($apiKeyId) : null;
		if($apiKeyId < 1 || !($key instanceof ApiKey)) {
			return JsonResponse::fail(__('Ошибка'), __('Укажите действующий API-ключ'), 'validation', 422);
		}

		$clientId     = bin2hex(random_bytes(16));
		$clientSecret = bin2hex(random_bytes(32));

		/** @var OauthClientRepository $clients */
		$clients = Application::instance()->database()->repository(OauthClient::class);
		$entity  = $clients->create([
			'client_id'     => $clientId,
			'client_secret' => password_hash($clientSecret, PASSWORD_DEFAULT),
			'name'          => $name !== '' ? $name : $clientId,
			'redirect_uri'  => $redirect ?? '',
			'grant_types'   => $grantTypes,
			'api_key_id'    => $apiKeyId,
			'active'        => 1,
		]);

		return JsonResponse::toast(__('OAuth-клиент создан'), [
			'id'              => $entity->id(),
			'name'            => $entity->name,
			'client_id'       => $clientId,
			'client_secret'   => $clientSecret,
			'api_key_preview' => $key->api,
			'grant_types'     => $entity->grant_types,
			'active'          => true,
		]);
	}

}
