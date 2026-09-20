<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Controller;

use DevCraft\Modules\DleApi\Services\DleApiConfig;

/**
 * Блок API-ключа на странице профиля.
 *
 * CSS и JS сайта — через siteAssets манифеста.
 * `focus=css` и `focus=js` оставлены пустыми, чтобы старые include не дублировали файлы.
 */
final class ProfileController {

	/**
	 * @param   string  $focus  html|css|js
	 */
	public function render(string $focus = 'html'): string {
		global $tpl, $is_logged, $member_id, $config, $dle_login_hash, $row, $user_group;

		if($focus === 'css' || $focus === 'js') {
			return '';
		}

		if(empty($is_logged) || empty($member_id['user_id'])) {
			return '';
		}

		if(!defined('DEVCRAFT_BOOTSTRAPPED')) {
			return '';
		}

		$cfg                 = DleApiConfig::all();
		$viewerId            = (int) $member_id['user_id'];
		$profileId           = (isset($row) && is_array($row))? (int) ($row['user_id'] ?? 0) : 0;
		$viewerCanModerate   = $this->isAdminViewer((array) $member_id, is_array($user_group ?? null)? $user_group : []);
		$isForeignModeration = $viewerCanModerate && $viewerId !== $profileId;

		if($profileId < 1 || ($viewerId !== $profileId && !$viewerCanModerate)) {
			return '';
		}

		if(empty($cfg['profile_allow_generate']) && empty($cfg['profile_show_field']) && !$isForeignModeration) {
			return '';
		}

		if(!isset($tpl) || !is_object($tpl)) {
			if(!class_exists('dle_template', false)) {
				require_once \DLEPlugins::Check(ENGINE_DIR . '/classes/templates.class.php');
			}

			$tpl      = new \dle_template();
			$tpl->dir = ROOT_DIR . '/templates/' . (string) ($config['skin'] ?? 'Default');
		}

		$skin = totranslit((string) ($config['skin'] ?? 'Default'), false, false);

		if(!is_dir(ROOT_DIR . '/templates/' . $skin . '/devcraft/dleapi')) {
			$skin = 'Default';
		}

		$restoreDir = $tpl->dir;
		$tpl->dir   = ROOT_DIR . '/templates/' . $skin;
		$e          = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

		$tpl->set('{user-hash}', $e((string) ($dle_login_hash ?? '')));
		$tpl->set('{profile-id}', (string) $profileId);
		$tpl->set('{viewer-id}', (string) $viewerId);
		$tpl->set('{admin-mode}', $isForeignModeration? '1' : '0');
		$tpl->set('{title}', $e(__('API-ключ')));
		$tpl->set('{new-label}', $e(__('Новый')));
		$tpl->set('{loading-label}', $e(__('Загрузка…')));
		$tpl->set('{key-label}', $e(__('Ключ')));
		$tpl->set('{copy-label}', $e(__('Копировать')));
		$tpl->set('{from-label}', $e(__('Когда')));
		$tpl->set('{until-label}', $e(__('Истекает')));
		$tpl->set('{level-label}', $e(__('Уровень')));
		$tpl->set('{request-label}', $e(__('Запросить ключ')));
		$tpl->set('{approve-label}', $e(__('Одобрить')));
		$tpl->set('{deny-label}', $e(__('Отказать')));
		$tpl->set('{request-hidden}', empty($cfg['profile_allow_generate'])? ' hidden' : '');

		$html = '';

		try {
			$tpl->result['content'] = '';
			$tpl->load_template('devcraft/dleapi/profile.tpl');
			$tpl->compile('content');
			$html = (string) ($tpl->result['content'] ?? '');
		} finally {
			$tpl->dir = $restoreDir;
		}

		return $html;
	}

	/**
	 * @param   array<string, mixed>         $member
	 * @param   array<int|string, mixed>     $groups
	 */
	private function isAdminViewer(array $member, array $groups): bool {
		$groupId = (int) ($member['user_group'] ?? 0);
		$group   = $groups[$groupId] ?? [];

		return $groupId === 1
			|| !empty($group['allow_all_edit'])
			|| !empty($group['allow_admin']);
	}

}
