<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Pages;

use DevCraft\Core\Application;
use DevCraft\Core\Abstracts\AbstractPage;
use DevCraft\Modules\DleApi\Models\ApiAccessLevel;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelRepository;
use DevCraft\Modules\DleApi\Services\ScopeTableCatalog;

/**
 * Страница уровней доступа API.
 */
final class AccessLevelsPage extends AbstractPage {

	public function handle(): array {
		$this->addBreadcrumb(__('Уровни доступа'));

		/** @var ApiAccessLevelRepository $repo */
		$repo = Application::instance()->database()->repository(ApiAccessLevel::class);

		return [
			'view' => 'dleapi/access_levels.twig',
			'data' => [
				'page_title'   => __('Уровни доступа'),
				'levels'       => $repo->all(),
				'scope_tables' => (new ScopeTableCatalog())->names(),
			],
		];
	}

}
