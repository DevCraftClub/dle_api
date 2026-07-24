<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Pages;

use DevCraft\Core\Config\Paths;
use DevCraft\Core\Support\DataManager;
use DevCraft\Core\Abstracts\AbstractPage;
use DevCraft\Core\Interfaces\SettingsPageInterface;

/**
 * Настройки DLE API.
 */
final class SettingsPage extends AbstractPage implements SettingsPageInterface {

	public function handle(): array {
		$this->addBreadcrumb(__('Настройки'));

		$configFile = Paths::config() . '/dleapi.json';

		if(!is_file($configFile)) {
			DataManager::saveConfig('dleapi', [
				'algo'      => 'sha256',
				'secret'    => '',
				'length'    => 32,
				'secure'    => true,
				'token_ttl' => 3600,
			]);
		}

		return [
			'view' => 'pages/settings.twig',
			'data' => [
				'page_title' => __('Настройки'),
			],
		];
	}

	public function supplementFormData(): array {
		return [];
	}

}
