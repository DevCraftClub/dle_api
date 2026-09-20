<?php

declare(strict_types=1);

/**
 * Публичный include блока API-ключа в профиле.
 *
 * {include file="devcraft/src/modules/DleApi/Controller/show_dleapi_profile.php"}
 *
 * focus=css и focus=js ничего не выводят: стили и скрипты — siteAssets и теги {devcraft-header} / {devcraft-scripts}.
 */

if(!defined('DATALIFEENGINE')) {
	header('HTTP/1.1 403 Forbidden');

	exit('Hacking attempt!');
}

if(!defined('DEVCRAFT_BOOTSTRAPPED')) {
	require_once DLEPlugins::Check(ROOT_DIR . '/devcraft/init.php');
}

if(!defined('DEVCRAFT_BOOTSTRAPPED')) {
	return;
}

$focus = isset($focus)? (string) $focus : 'html';

echo (new DevCraft\Modules\DleApi\Controller\ProfileController())->render($focus);
