<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Ajax;

use DevCraft\Core\Application;
use DevCraft\Core\Http\AjaxRequest;
use DevCraft\Core\Http\JsonResponse;
use DevCraft\Core\Interfaces\AjaxHandlerInterface;
use DevCraft\Core\Interfaces\ResponseInterface;
use DevCraft\Modules\DleApi\Models\ApiKeyRequest;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRequestRepository;
use DevCraft\Modules\DleApi\Services\DleApiConfig;
use DevCraft\Modules\DleApi\Services\KeyNotifyDelivery;
use DevCraft\Modules\DleApi\Services\ProfileKeyService;

/**
 * Одобрение / отказ заявки на API-ключ.
 */
final class DecideKeyRequestHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		global $member_id;

		$id      = (int) ($request->data['id'] ?? 0);
		$approve = !empty($request->data['approve']);
		$adminId = (int) ($member_id['user_id'] ?? 0);

		if($id < 1) {
			return JsonResponse::fail(__('Ошибка'), __('Некорректный идентификатор'), 'validation', 422);
		}

		/** @var ApiKeyRequestRepository $repo */
		$repo = Application::instance()->database()->repository(ApiKeyRequest::class);
		$req  = $repo->find($id);
		if($req === null || $req->status !== 'pending') {
			return JsonResponse::fail(__('Ошибка'), __('Заявка не найдена или уже обработана'), 'not_found', 404);
		}

		$profile = new ProfileKeyService();
		$notify  = new KeyNotifyDelivery();
		$shown   = null;

		if($approve) {
			$levelId = $req->access_level_id > 0 ? $req->access_level_id : 0;
			$key     = $profile->generateForUser($req->user_id, $levelId);
			$shown   = DleApiConfig::isDemoMode() ? '***' : $key->api;
			$repo->decide($req, 'approved', $adminId);
			$notify->notifyDecision($req->user_id, true, [
				'{%user_id%}' => (string) $req->user_id,
				'{%api_key%}' => $key->api,
				'{%subject%}' => __('API-ключ одобрен'),
			]);
		} else {
			$repo->decide($req, 'denied', $adminId);
			$notify->notifyDecision($req->user_id, false, [
				'{%user_id%}' => (string) $req->user_id,
				'{%subject%}' => __('API-ключ отклонён'),
			]);
		}

		return JsonResponse::toast(
			$approve ? __('Одобрено') : __('Отклонено'),
			['id' => $id, 'status' => $approve ? 'approved' : 'denied', 'api_key' => $shown],
		);
	}

}
