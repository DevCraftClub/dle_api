<?php

declare(strict_types=1);

/**
 * Блок API-ключа в профиле и assets (focus=html|js|css).
 *
 * {include file="engine/modules/devcraft/dleapi_profile.php"}
 * {include file="engine/modules/devcraft/dleapi_profile.php?focus=js"}
 *
 * DLE подключает этот файл через dle_template::load_file(), который перед
 * include клонирует вызывающий $tpl (см. templates.class.php) — внутри
 * доступен настоящий dle_template, а не самодельный strtr()-рендер.
 *
 * @var \dle_template $tpl
 * @var string        $focus  html|js|css
 */

use DevCraft\Modules\DleApi\Services\DleApiConfig;

if(!defined('DATALIFEENGINE')) {
	die('Hacking attempt!');
}

if(!function_exists('__')) {
	/**
	 * @param   array<string, mixed>  $params
	 */
	function __(string $phrase, array $params = [], int $count = 0): string {
		return $phrase;
	}
}

global $is_logged, $member_id, $config, $dle_login_hash, $row, $user_group;

$focus   = isset($focus)? $focus : 'html';
$homeUrl = rtrim((string) ($config['http_home_url'] ?? '/'), '/') . '/';

$cfg = [];
if(defined('DEVCRAFT_BOOTSTRAPPED')) {
	$cfg = DleApiConfig::all();
}

$isAdminViewer = static function(array $member, array $groups): bool {
	$groupId = (int) ($member['user_group'] ?? 0);
	$group   = $groups[$groupId] ?? [];

	return $groupId === 1
	       || !empty($group['allow_all_edit'])
	       || !empty($group['allow_admin']);
};

$skin = totranslit((string) ($config['skin'] ?? 'Default'), false, false);
if(!is_dir(ROOT_DIR . '/templates/' . $skin . '/devcraft/dleapi')) {
	$skin = 'Default';
}

$tpl->dir    = ROOT_DIR . '/templates/' . $skin;
$modUrl      = $homeUrl . 'templates/' . $skin . '/devcraft/dleapi';
$ajaxUrl     = $homeUrl . 'devcraft/ajax.php';
$dcPublicUrl = $homeUrl . 'devcraft/src/templates/core/assets/js/dc_public.js';

if($focus === 'css') {
	echo '<link rel="stylesheet" href="' . htmlspecialchars($modUrl . '/profile.css', ENT_QUOTES, 'UTF-8') . '">' . "\n";

	return;
}

if($focus === 'js') {
	$dcPublicPath = ROOT_DIR . '/devcraft/src/templates/core/assets/js/dc_public.js';
	if(is_file($dcPublicPath)) {
		$dcPublicUrl .= '?v=' . filemtime($dcPublicPath);
	}

	echo '<meta name="dc-ajax-base" content="' . htmlspecialchars($ajaxUrl, ENT_QUOTES, 'UTF-8') . '">' . "\n";
	echo '<script src="' . htmlspecialchars($dcPublicUrl, ENT_QUOTES, 'UTF-8') . '"></script>' . "\n";
	$jsPath = $tpl->dir . '/devcraft/dleapi/dleapi_profile.js';
	if(is_file($jsPath)) {
		echo "<script>\n" . file_get_contents($jsPath) . "\n</script>\n";
	}

	return;
}

if(empty($is_logged) || empty($member_id['user_id'])) {
	return;
}

$viewerId          = (int) $member_id['user_id'];
$profileId         = (isset($row) && is_array($row))? (int) ($row['user_id'] ?? 0) : 0;
$viewerCanModerate = $isAdminViewer((array) $member_id, is_array($user_group ?? null)? $user_group : []);
if($profileId < 1 || ($viewerId !== $profileId && !$viewerCanModerate)) {
	return;
}

$isForeignModerationView = $viewerCanModerate && $viewerId !== $profileId;
if(empty($cfg['profile_allow_generate']) && empty($cfg['profile_show_field']) && !$isForeignModerationView) {
	return;
}

$e = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

echo '<link rel="stylesheet" href="' . $e($modUrl . '/profile.css') . '">' . "\n";

$tpl->set('{user-hash}', $e((string) ($dle_login_hash ?? '')));
$tpl->set('{profile-id}', (string) $profileId);
$tpl->set('{viewer-id}', (string) $viewerId);
$tpl->set('{admin-mode}', $isForeignModerationView? '1' : '0');
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

$tpl->load_template('devcraft/dleapi/profile.tpl');
$tpl->compile('content');
echo $tpl->result['content'];
