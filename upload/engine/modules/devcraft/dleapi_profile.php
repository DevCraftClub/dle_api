<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

/**
 * Блок API-ключа в профиле и assets (focus=html|js).
 * Подключение в теме — см. документацию DLE API / install.
 *
 * @var string $focus  html|js
 */
if(!defined('DATALIFEENGINE')) {
	die('Hacking attempt!');
}

if(!function_exists('__')) {
	function __(string $phrase, array $params = [], int $count = 0): string {
		return $phrase;
	}
}

global $is_logged, $member_id, $config, $dle_login_hash, $row;

$focus   = isset($focus) ? (string) $focus : 'html';
$cfgPath = ROOT_DIR . '/devcraft/config/dleapi.json';
$cfg     = is_file($cfgPath) ? (json_decode((string) file_get_contents($cfgPath), true) ?: []) : [];
$homeUrl = rtrim((string) ($config['http_home_url'] ?? '/'), '/') . '/';

if($focus === 'js') {
	// Публичный клиент + UI профиля; JS inline — обход кэша NPM/assets.
	$ajaxUrl       = $homeUrl . 'devcraft/ajax.php';
	$dcPublicPath  = ROOT_DIR . '/devcraft/src/templates/core/assets/js/dc_public.js';
	$dcPublicUrl   = $homeUrl . 'devcraft/src/templates/core/assets/js/dc_public.js';
	$profileJsPath = ROOT_DIR . '/devcraft/src/modules/DleApi/Public/dleapi_profile.js';

	if(is_file($dcPublicPath)) {
		$dcPublicUrl .= '?v=' . (string) filemtime($dcPublicPath);
	}

	echo '<meta name="dc-ajax-base" content="' . htmlspecialchars($ajaxUrl, ENT_QUOTES, 'UTF-8') . '">' . "\n";
	echo '<script src="' . htmlspecialchars($dcPublicUrl, ENT_QUOTES, 'UTF-8') . '"></script>' . "\n";
	if(is_file($profileJsPath)) {
		echo "<script>\n" . file_get_contents($profileJsPath) . "\n</script>\n";
	}

	return;
}

if(empty($is_logged) || empty($member_id['user_id'])) {
	return;
}

// Только свой профиль: [not-logged] у админа открыт и на чужих страницах.
$viewerId  = (int) $member_id['user_id'];
$profileId = (isset($row) && is_array($row)) ? (int) ($row['user_id'] ?? 0) : 0;
if($profileId < 1 || $viewerId !== $profileId) {
	return;
}

if(empty($cfg['profile_allow_generate']) && empty($cfg['profile_show_field'])) {
	return;
}

$userHash = htmlspecialchars((string) ($dle_login_hash ?? ''), ENT_QUOTES, 'UTF-8');

echo '<div class="dleapi-profile-key" id="dleapi-profile-key" data-user-hash="' . $userHash . '">';
echo '<h3>' . htmlspecialchars(__('API-ключ'), ENT_QUOTES, 'UTF-8') . '</h3>';
echo '<p class="dleapi-profile-status" id="dleapi-profile-status">' . htmlspecialchars(__('Загрузка…'), ENT_QUOTES, 'UTF-8') . '</p>';
if(!empty($cfg['profile_allow_generate'])) {
	echo '<button type="button" class="btn btn-primary" id="dleapi-profile-request">' . htmlspecialchars(__('Запросить / сгенерировать ключ'), ENT_QUOTES, 'UTF-8') . '</button>';
}
echo '</div>';
|||||||
=======
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
>>>>>>> Current commit: Начало обновления до api v2
