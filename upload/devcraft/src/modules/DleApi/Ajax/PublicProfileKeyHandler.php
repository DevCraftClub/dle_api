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
