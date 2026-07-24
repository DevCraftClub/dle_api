<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Ajax;

use DevCraft\Core\Http\AjaxRequest;
use DevCraft\Core\Http\JsonResponse;
use DevCraft\Core\Interfaces\AjaxHandlerInterface;
use DevCraft\Core\Interfaces\ResponseInterface;
use DevCraft\Core\Application;
use DevCraft\Modules\DleApi\Models\ApiAccessLevel;
use DevCraft\Modules\DleApi\Models\ApiKey;
use DevCraft\Modules\DleApi\Models\ApiKeyRequest;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelRepository;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRepository;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRequestRepository;
use DevCraft\Modules\DleApi\Services\DleApiConfig;
use DevCraft\Modules\DleApi\Services\KeyNotifyDelivery;
use DevCraft\Modules\DleApi\Services\ProfileKeyService;

/**
 * Публичный AJAX: статус / запрос API-ключа для текущего пользователя сайта.
 */
final class PublicProfileKeyHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		global $is_logged, $member_id;

		if(empty($is_logged) || !is_array($member_id ?? null)) {
			return JsonResponse::fail(__('Ошибка'), __('Требуется авторизация'), 'auth', 401);
		}

		$viewerId = (int) ($member_id['user_id'] ?? 0);
		if($viewerId < 1) {
			return JsonResponse::fail(__('Ошибка'), __('Требуется авторизация'), 'auth', 401);
		}

		$action = (string) ($request->data['action'] ?? 'status');
		$cfg    = DleApiConfig::all();
		$isAdmin = (int) ($member_id['user_group'] ?? 0) === 1;
		$targetUserId = (int) ($request->data['profile_user_id'] ?? $viewerId);
		if($targetUserId < 1) {
			$targetUserId = $viewerId;
		}
		if($targetUserId !== $viewerId && !$isAdmin) {
			return JsonResponse::fail(__('Ошибка'), __('Недостаточно прав'), 'forbidden', 403);
		}

		if($action === 'request' || $action === 'generate') {
			if($targetUserId !== $viewerId) {
				return JsonResponse::fail(__('Ошибка'), __('Нельзя запрашивать ключ за другого пользователя'), 'forbidden', 403);
			}
			$result = (new ProfileKeyService())->requestOrGenerate($viewerId);
			if(empty($result['ok'])) {
				return JsonResponse::fail(__('Ошибка'), (string) ($result['message'] ?? __('Не удалось')), 'validation', 422, $result);
			}
			if(DleApiConfig::isDemoMode() && isset($result['key'])) {
				$result['key'] = '***';
			}

			return JsonResponse::ok($result);
		}

		if($action === 'moderate') {
			if(!$isAdmin) {
				return JsonResponse::fail(__('Ошибка'), __('Недостаточно прав для модерации'), 'forbidden', 403);
			}

			$approve = !empty($request->data['approve']);
			$reqId = (int) ($request->data['request_id'] ?? 0);
			/** @var ApiKeyRequestRepository $repo */
			$repo = Application::instance()->database()->repository(ApiKeyRequest::class);
			$pending = $reqId > 0 ? $repo->find($reqId) : $repo->findPendingByUser($targetUserId);

			if($pending === null || $pending->status !== 'pending') {
				return JsonResponse::fail(__('Ошибка'), __('Заявка не найдена или уже обработана'), 'not_found', 404);
			}

			$profile = new ProfileKeyService();
			$notify  = new KeyNotifyDelivery();

			if($approve) {
				$key = $profile->generateForUser($pending->user_id, $pending->access_level_id > 0 ? $pending->access_level_id : 0);
				$repo->decide($pending, 'approved', $viewerId);
				$notify->notifyDecision($pending->user_id, true, [
					'{%user_id%}' => (string) $pending->user_id,
					'{%api_key%}' => $key->api,
					'{%subject%}' => __('API-ключ одобрен'),
				]);
			} else {
				$repo->decide($pending, 'denied', $viewerId);
				$notify->notifyDecision($pending->user_id, false, [
					'{%user_id%}' => (string) $pending->user_id,
					'{%api_key%}' => '',
					'{%subject%}' => __('API-ключ отклонён'),
				]);
			}

			return JsonResponse::ok($this->buildStatusPayload($targetUserId, $viewerId, $isAdmin, $cfg));
		}

		return JsonResponse::ok($this->buildStatusPayload($targetUserId, $viewerId, $isAdmin, $cfg));
	}

	private function findActiveKey(int $userId): ?ApiKey {
		/** @var ApiKeyRepository $keys */
		$keys = Application::instance()->database()->repository(ApiKey::class);

		return $keys->findActiveByUserId($userId);
	}

	private function findPendingRequest(int $userId): ?ApiKeyRequest {
		/** @var ApiKeyRequestRepository $req */
		$req = Application::instance()->database()->repository(ApiKeyRequest::class);

		return $req->findPendingByUser($userId);
	}

	/**
	 * @param array<string, mixed> $cfg
	 * @return array<string, mixed>
	 */
	private function buildStatusPayload(int $targetUserId, int $viewerId, bool $isAdmin, array $cfg): array {
		$existing = $this->findActiveKey($targetUserId);
		/** @var ApiKeyRequestRepository $req */
		$req = Application::instance()->database()->repository(ApiKeyRequest::class);
		$latest = $req->latestByUser($targetUserId);
		$pending = $latest !== null && $latest->status === 'pending' ? $latest : null;
		$mode = $targetUserId === $viewerId ? 'self' : ($isAdmin ? 'moderation' : 'readonly');

		$status = 'idle';
		if($existing !== null) {
			$status = 'approved';
		} elseif($pending !== null) {
			$status = 'pending';
		} elseif($latest !== null && $latest->status === 'denied') {
			$status = 'denied';
		}

		return [
			'user_id'                => $viewerId,
			'profile_user_id'        => $targetUserId,
			'profile_show_field'     => !empty($cfg['profile_show_field']),
			'profile_allow_generate' => !empty($cfg['profile_allow_generate']),
			'viewer_is_admin'        => $isAdmin,
			'mode'                   => $mode,
			'status'                 => $status,
			'has_key'                => $existing !== null,
			'has_pending_request'    => $pending !== null,
			'request'                => $latest !== null ? [
				'id'         => $latest->id(),
				'status'     => $latest->status,
				'created_at' => $latest->createdAt->format(DATE_ATOM),
				'decided_at' => $latest->decided_at?->format(DATE_ATOM),
				'message'    => $latest->message,
			] : null,
			'key'                    => $this->buildKeyPayload($existing),
			'access_level'           => $existing !== null ? $this->buildAccessLevelPayload((int) $existing->access_level_id) : null,
		];
	}

	private function buildKeyPayload(?ApiKey $key): ?array {
		if($key === null) {
			return null;
		}

		$plain = DleApiConfig::isDemoMode() ? '***' : $key->api;
		$masked = strlen($plain) > 8
			? substr($plain, 0, 4) . str_repeat('*', max(4, strlen($plain) - 8)) . substr($plain, -4)
			: str_repeat('*', max(4, strlen($plain)));

		return [
			'id'         => $key->id(),
			'key'        => $plain,
			'masked_key' => $masked,
			'valid_from' => $key->createdAt->format(DATE_ATOM),
			'valid_to'   => null,
		];
	}

	private function buildAccessLevelPayload(int $levelId): ?array {
		if($levelId < 1) {
			return null;
		}

		/** @var ApiAccessLevelRepository $repo */
		$repo = Application::instance()->database()->repository(ApiAccessLevel::class);
		$level = $repo->find($levelId);
		if($level === null) {
			return null;
		}

		return [
			'id'          => $level->id(),
			'name'        => $level->name,
			'cheater'     => $level->cheater,
			'own_only'    => $level->own_only,
			'premoderate' => $level->premoderate,
		];
	}

}
|||||||
=======
<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Ajax;

use DevCraft\Core\Http\AjaxRequest;
use DevCraft\Core\Http\JsonResponse;
use DevCraft\Core\Interfaces\AjaxHandlerInterface;
use DevCraft\Core\Interfaces\ResponseInterface;
use DevCraft\Modules\DleApi\Services\DleApiConfig;
use DevCraft\Modules\DleApi\Services\ProfileKeyService;

/**
 * Публичный AJAX: статус / запрос API-ключа для текущего пользователя сайта.
 */
final class PublicProfileKeyHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		global $is_logged, $member_id;

		if(empty($is_logged) || !is_array($member_id ?? null)) {
			return JsonResponse::fail(__('Ошибка'), __('Требуется авторизация'), 'auth', 401);
		}

		$userId = (int) ($member_id['user_id'] ?? 0);
		if($userId < 1) {
			return JsonResponse::fail(__('Ошибка'), __('Требуется авторизация'), 'auth', 401);
		}

		$action = (string) ($request->data['action'] ?? 'status');
		$cfg    = DleApiConfig::all();

		if($action === 'request' || $action === 'generate') {
			$result = (new ProfileKeyService())->requestOrGenerate($userId);
			if(empty($result['ok'])) {
				return JsonResponse::fail(__('Ошибка'), (string) ($result['message'] ?? __('Не удалось')), 'validation', 422, $result);
			}
			if(DleApiConfig::isDemoMode() && isset($result['key'])) {
				$result['key'] = '***';
			}

			return JsonResponse::ok($result);
		}

		return JsonResponse::ok([
			'user_id'               => $userId,
			'profile_show_field'    => !empty($cfg['profile_show_field']),
			'profile_allow_generate'=> !empty($cfg['profile_allow_generate']),
			'profile_xfield'        => (string) ($cfg['profile_xfield'] ?? ''),
			'has_key_in_xfield'     => $this->hasKeyInXfield($userId, (string) ($cfg['profile_xfield'] ?? '')),
		]);
	}

	private function hasKeyInXfield(int $userId, string $field): bool {
		if($field === '') {
			return false;
		}
		global $db;
		$row = $db->super_query('SELECT xfields FROM ' . USERPREFIX . '_users WHERE user_id=' . $userId);
		$xf  = (string) ($row['xfields'] ?? '');
		foreach(explode('||', $xf) as $p) {
			if(str_starts_with($p, $field . '|') && strlen($p) > strlen($field) + 1) {
				return true;
			}
		}

		return false;
	}

}
>>>>>>> Current commit: Начало обновления до api v2
