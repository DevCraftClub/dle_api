<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Pages;

use DevCraft\Core\Application;
use DevCraft\Core\Abstracts\AbstractPage;
use DevCraft\Modules\DleApi\Models\ApiAccessLevel;
use DevCraft\Modules\DleApi\Models\ApiAccessLevelGroup;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelGroupRepository;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelRepository;
use DevCraft\Core\Support\DleDataService;

/**
 * Синхронизация групп DLE ↔ уровни доступа.
 */
final class AccessSyncPage extends AbstractPage {

	public function handle(): array {
		$this->addBreadcrumb(__('Синхронизация с группами'));

		/** @var ApiAccessLevelRepository $levels */
		$levels = Application::instance()->database()->repository(ApiAccessLevel::class);
		/** @var ApiAccessLevelGroupRepository $mapRepo */
		$mapRepo = Application::instance()->database()->repository(ApiAccessLevelGroup::class);

		$map = [];
		foreach($mapRepo->all() as $row) {
			$map[$row->user_group_id] = $row->access_level_id;
		}

		$groups = [];
		foreach(DleDataService::groups() as $id => $name) {
			$id = (int) $id;
			if($id < 1) {
				continue;
			}
			$groups[] = [
				'id'               => $id,
				'name'             => (string) $name,
				'access_level_id'  => (int) ($map[$id] ?? 0),
			];
		}

		return [
			'view' => 'dleapi/access_sync.twig',
			'data' => [
				'page_title' => __('Синхронизация с группами'),
				'groups'     => $groups,
				'levels'     => $levels->allActive(),
			],
		];
	}

}
