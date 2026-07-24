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
 * Данные уровня и его scopes.
 */
final class GetAccessLevelHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		$id = (int) ($request->data['id'] ?? 0);
		if($id < 1) {
			return JsonResponse::fail(__('Ошибка'), __('Некорректный идентификатор'), 'validation', 422);
		}

		/** @var ApiAccessLevelRepository $repo */
		$repo  = Application::instance()->database()->repository(ApiAccessLevel::class);
		$level = $repo->find($id);
		if($level === null) {
			return JsonResponse::fail(__('Ошибка'), __('Уровень не найден'), 'not_found', 404);
		}

		/** @var ApiAccessLevelScopeRepository $scopes */
		$scopes = Application::instance()->database()->repository(ApiAccessLevelScope::class);
		$tables = [];
		foreach($scopes->forLevel($id) as $row) {
			$t = (string) ($row->scope_table ?? '');
			if($t === '') {
				continue;
			}
			$tables[$t] = [
				'read'   => $row->can_read,
				'write'  => $row->can_write,
				'edit'   => $row->can_edit,
				'delete' => $row->can_delete,
			];
		}

		return JsonResponse::ok([
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
			'tables'         => $tables,
		]);
	}

}
