<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Ajax;

use DevCraft\Core\Application;
use DevCraft\Core\Http\AjaxRequest;
use DevCraft\Core\Http\JsonResponse;
use DevCraft\Core\Interfaces\AjaxHandlerInterface;
use DevCraft\Core\Interfaces\ResponseInterface;
use DevCraft\Modules\DleApi\Models\ApiAccessLevel;
use DevCraft\Modules\DleApi\Models\ApiAccessLevelScope;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelRepository;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelScopeRepository;

/**
 * Создание / обновление уровня доступа.
 */
final class SaveAccessLevelHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		$id     = (int) ($request->data['id'] ?? 0);
		$tables = is_array($request->data['tables'] ?? null) ? $request->data['tables'] : [];
		$name   = trim((string) ($request->data['name'] ?? ''));
		if($name === '') {
			return JsonResponse::fail(__('Ошибка'), __('Укажите название уровня'), 'validation', 422);
		}

		/** @var ApiAccessLevelRepository $repo */
		$repo = Application::instance()->database()->repository(ApiAccessLevel::class);
		$data = [
			'name'           => $name,
			'active'         => !empty($request->data['active']),
			'sort'           => (int) ($request->data['sort'] ?? 0),
			'premoderate'    => !empty($request->data['premoderate']),
			'own_only'       => array_key_exists('own_only', $request->data) ? !empty($request->data['own_only']) : true,
			'cheater'        => !empty($request->data['cheater']),
			'mask_ip'        => array_key_exists('mask_ip', $request->data) ? !empty($request->data['mask_ip']) : true,
			'mask_passwords' => array_key_exists('mask_passwords', $request->data) ? !empty($request->data['mask_passwords']) : true,
			'mask_personal'  => array_key_exists('mask_personal', $request->data) ? !empty($request->data['mask_personal']) : true,
		];

		if($id > 0) {
			$level = $repo->find($id);
			if($level === null) {
				return JsonResponse::fail(__('Ошибка'), __('Уровень не найден'), 'not_found', 404);
			}
			$level = $repo->update($level, $data);
		} else {
			$level = $repo->create($data);
		}

		/** @var ApiAccessLevelScopeRepository $scopes */
		$scopes = Application::instance()->database()->repository(ApiAccessLevelScope::class);
		$scopes->replaceForLevel($level->id(), $tables);

		return JsonResponse::toast(__('Сохранено'), [
			'id'             => $level->id(),
			'name'           => $level->name,
			'active'         => $level->active,
			'sort'           => $level->sort,
			'premoderate'    => $level->premoderate,
			'own_only'       => $level->own_only,
			'cheater'        => $level->cheater,
			'mask_ip'        => $level->mask_ip,
			'mask_passwords' => $level->mask_passwords,
			'mask_personal'  => $level->mask_personal,
		]);
	}

}
