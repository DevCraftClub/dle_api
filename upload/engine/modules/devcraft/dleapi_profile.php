<?php

declare(strict_types=1);

/**
 * Блок API-ключа в профиле / отдельной странице.
 *
 * {include file="engine/modules/devcraft/dleapi_profile.php"}
 */

if(!defined('DATALIFEENGINE')) {
	die('Hacking attempt!');
}

if(!function_exists('__')) {
	function __(string $phrase, array $params = [], int $count = 0): string {
		return $phrase;
	}
}

global $is_logged, $member_id, $config, $dle_login_hash;

if(empty($is_logged) || empty($member_id['user_id'])) {
	echo '<div class="dleapi-profile-key"><p>' . htmlspecialchars(__('Войдите, чтобы управлять API-ключом'), ENT_QUOTES, 'UTF-8') . '</p></div>';

	return;
}

$cfgPath = ROOT_DIR . '/devcraft/config/dleapi.json';
$cfg     = is_file($cfgPath) ? (json_decode((string) file_get_contents($cfgPath), true) ?: []) : [];

if(empty($cfg['profile_allow_generate']) && empty($cfg['profile_show_field'])) {
	return;
}

$homeUrl     = rtrim((string) ($config['http_home_url'] ?? '/'), '/') . '/';
$dcPublicUrl = $homeUrl . 'devcraft/src/templates/core/assets/js/dc_public.js';
$jsUrl       = $homeUrl . 'devcraft/src/modules/DleApi/Public/dleapi_profile.js';
$userHash    = htmlspecialchars((string) ($dle_login_hash ?? ''), ENT_QUOTES, 'UTF-8');

echo '<div class="dleapi-profile-key" id="dleapi-profile-key" data-user-hash="' . $userHash . '">';
echo '<h3>' . htmlspecialchars(__('API-ключ'), ENT_QUOTES, 'UTF-8') . '</h3>';
echo '<p class="dleapi-profile-status" id="dleapi-profile-status">' . htmlspecialchars(__('Загрузка…'), ENT_QUOTES, 'UTF-8') . '</p>';
if(!empty($cfg['profile_allow_generate'])) {
	echo '<button type="button" class="btn btn-primary" id="dleapi-profile-request">' . htmlspecialchars(__('Запросить / сгенерировать ключ'), ENT_QUOTES, 'UTF-8') . '</button>';
}
echo '</div>';
echo '<script src="' . htmlspecialchars($dcPublicUrl, ENT_QUOTES, 'UTF-8') . '"></script>';
echo '<script src="' . htmlspecialchars($jsUrl, ENT_QUOTES, 'UTF-8') . '"></script>';
