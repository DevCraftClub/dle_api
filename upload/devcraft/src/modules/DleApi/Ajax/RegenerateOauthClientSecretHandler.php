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
 * Перевыпуск client_secret OAuth-клиента (старый секрет перестаёт работать).
 */
final class RegenerateOauthClientSecretHandler implements AjaxHandlerInterface {

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

		$plain = bin2hex(random_bytes(32));
		$repo->rotateSecret($client, password_hash($plain, PASSWORD_DEFAULT));

		return JsonResponse::toast(__('client_secret пересоздан'), [
			'id'            => $client->id(),
			'client_id'     => $client->client_id,
			'client_secret' => $plain,
		]);
	}

}
