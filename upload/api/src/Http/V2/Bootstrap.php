<?php

declare(strict_types=1);

/**
 * Bootstrap API v2: хелперы и автозагрузка (composer PSR-4 DleApi\).
 *
 * Schema / Fluent / Xfield / `DcApi` приходят из DevCraft Admin
 * (`devcraft/src/sdk/dle/`, автозагрузка `devcraft/vendor/autoload.php`).
 *
 * @since 200.1.1 SDK больше не дублируется в пакете API.
 */

if(!defined('DATALIFEENGINE')) {
	exit(__('Попытка взлома!'));
}

if(is_file(API_ROOT . '/vendor/autoload.php')) {
	require_once API_ROOT . '/vendor/autoload.php';
}

if(!function_exists('dle_api_db')) {
	require_once DLEPlugins::Check(ROOT_DIR . '/devcraft/src/sdk/dle/bootstrap.php');
}

require_once DLEPlugins::Check(API_ROOT . '/src/Http/V2/Helpers.php');
