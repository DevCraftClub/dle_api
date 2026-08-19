<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Pages;

use DLEPlugins;
use DevCraft\Core\Application;
use DevCraft\Core\Config\Paths;
use DevCraft\Core\Support\DataManager;
use DevCraft\Core\Abstracts\AbstractPage;
use DevCraft\Core\Interfaces\SettingsPageInterface;
use DevCraft\Modules\DleApi\Services\KeyNotifyDelivery;

/**
 * Настройки DLE API.
 */
final class SettingsPage extends AbstractPage implements SettingsPageInterface {

	public function handle(): array {
		global $config, $dle_login_hash;

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

		$current = DataManager::getConfig('dleapi');
		$merged  = KeyNotifyDelivery::loadEditorConfig(is_array($current) ? $current : []);
		if(!is_array($current) || $merged !== $current) {
			DataManager::saveConfig('dleapi', $merged);
		}

		$dleHome = rtrim((string) ($config['http_home_url'] ?? '/'), '/') . '/';

		return [
			'view' => 'dleapi/settings.twig',
			'data' => [
				'page_title'       => __('Настройки'),
				'dle_home'         => $dleHome,
				'dle_skin'         => (string) ($config['skin'] ?? 'Default'),
				'pm_wysiwyg'       => !empty($config['allow_pm_wysiwyg']),
				'pm_editor_script' => $this->buildPmEditorScript(),
				'dle_login_hash'   => (string) ($dle_login_hash ?? ''),
			],
		];
	}

	public function supplementFormData(): array {
		$dleData = Application::instance()->dleData();
		$groups  = [];
		$users   = [];

		foreach($dleData->groups() as $id => $name) {
			$id = (int) $id;
			if($id < 1) {
				continue;
			}
			$groups[(string) $id] = (string) $name;
		}

		foreach($dleData->users() as $row) {
			$id    = (string) ((int) ($row['user_id'] ?? 0));
			$name  = trim((string) ($row['name'] ?? ''));
			$email = trim((string) ($row['email'] ?? ''));

			if($id === '0' || $name === '') {
				continue;
			}

			$users[$id] = $email !== '' ? $name . ' <' . $email . '>' : $name;
		}

		return [
			'notify_group_ids' => $groups,
			'notify_user_ids'  => $users,
		];
	}

	private function buildPmEditorScript(): string {
		global $config, $member_id, $user_group, $lang, $tpl;

		if(empty($config['allow_pm_wysiwyg'])) {
			return '';
		}

		if(!is_array($member_id ?? null)) {
			$member_id = ['user_group' => 1, 'user_id' => 1, 'name' => ''];
		}

		if(!is_array($user_group ?? null) || $user_group === []) {
			$user_group = [
				1 => ['allow_url' => 1, 'allow_image' => 1, 'group_name' => 'Admin'],
			];
		}

		if(!is_array($lang ?? null)) {
			$lang = ['language_code' => 'ru', 'direction' => 'ltr'];
		} else {
			$lang['language_code'] = $lang['language_code'] ?? 'ru';
			$lang['direction']     = $lang['direction'] ?? 'ltr';
		}

		if(!isset($tpl) || !is_object($tpl)) {
			if(!class_exists('dle_template', false)) {
				require_once DLEPlugins::Check(ENGINE_DIR . '/classes/templates.class.php');
			}
			$tpl             = new \dle_template();
			$tpl->smartphone = false;
			$tpl->tablet     = false;
		}

		$is_pm_ajax_mode        = true;
		$comments_mobile_editor = false;

		/** @noinspection PhpIncludeInspection */
		include DLEPlugins::Check(ENGINE_DIR . '/editor/pm.php');

		return isset($editor_scrips) ? (string) $editor_scrips : '';
	}

}
