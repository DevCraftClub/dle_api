<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Ajax;

use DevCraft\Core\Application;
use DevCraft\Core\Http\AjaxRequest;
use DevCraft\Core\Http\JsonResponse;
use DevCraft\Core\Interfaces\AjaxHandlerInterface;
use DevCraft\Core\Interfaces\ResponseInterface;
use DevCraft\Modules\DleApi\Models\ApiAccessLevelGroup;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelGroupRepository;

/**
 * Сохранение map группа DLE → уровень доступа.
 */
final class SaveAccessSyncHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		$map = is_array($request->data['map'] ?? null) ? $request->data['map'] : [];

		/** @var ApiAccessLevelGroupRepository $repo */
		$repo = Application::instance()->database()->repository(ApiAccessLevelGroup::class);

		foreach($map as $groupId => $levelId) {
			$groupId = (int) $groupId;
			$levelId = (int) $levelId;
			if($groupId < 1) {
				continue;
			}
			if($levelId < 1) {
				$repo->clear($groupId);
			} else {
				$repo->upsert($groupId, $levelId);
			}
		}

		return JsonResponse::toast(__('Синхронизация сохранена'), ['saved' => true]);
	}

}
