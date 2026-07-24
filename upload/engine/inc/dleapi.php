<?php

declare(strict_types=1);

/**
 * Точка входа админ-модуля DLE API (DevCraft Admin).
 */

if(!defined('DATALIFEENGINE') || !defined('LOGGED_IN')) {
	header('HTTP/1.1 403 Forbidden');
	header('Location: ../../');

	exit('Hacking attempt!');
}

require_once DLEPlugins::Check(ROOT_DIR . '/devcraft/init.php');

if(!defined('DEVCRAFT_BOOTSTRAPPED')) {
	return;
}

DevCraft\Core\Application::instance()->runAdmin(moduleDir: 'DleApi');
