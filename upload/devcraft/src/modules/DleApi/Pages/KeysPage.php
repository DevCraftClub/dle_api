<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Pages;

use DevCraft\Core\Application;
use DevCraft\Core\Abstracts\AbstractPage;
use DevCraft\Modules\DleApi\Models\ApiAccessLevel;
use DevCraft\Modules\DleApi\Models\ApiKey;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelRepository;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRepository;
use DevCraft\Modules\DleApi\Services\ScopeTableCatalog;

/**
 * Страница управления API-ключами.
 */
final class KeysPage extends AbstractPage {

	public function handle(): array {
		$this->addBreadcrumb(__('API-ключи'));

		/** @var ApiKeyRepository $repo */
		$repo = Application::instance()->database()->repository(ApiKey::class);
		/** @var ApiAccessLevelRepository $levels */
		$levels = Application::instance()->database()->repository(ApiAccessLevel::class);

		return [
			'view' => 'dleapi/keys.twig',
			'data' => [
				'page_title'   => __('API-ключи'),
				'keys'         => $repo->all(),
				'users'        => $this->userOptions(),
				'levels'       => $levels->allActive(),
				'scope_tables' => (new ScopeTableCatalog())->names(),
			],
		];
	}

	/**
	 * @return list<array{id: int, label: string}>
	 */
	private function userOptions(): array {
		$options = [
			['id' => 0, 'label' => '0 — ' . __('гость')],
		];

		foreach(Application::instance()->dleData()->users() as $row) {
			$id = (int) ($row['user_id'] ?? 0);

			if($id < 1) {
				continue;
			}

			$name  = trim((string) ($row['name'] ?? ''));
			$email = trim((string) ($row['email'] ?? ''));
			$label = $name !== '' ? $name : ('#' . $id);

			if($email !== '') {
				$label .= ' <' . $email . '>';
			}

			$options[] = [
				'id'    => $id,
				'label' => '#' . $id . ' — ' . $label,
			];
		}

		return $options;
	}

}
