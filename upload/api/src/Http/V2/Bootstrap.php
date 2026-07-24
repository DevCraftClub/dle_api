<?php

declare(strict_types=1);

/**
 * Bootstrap API v2: хелперы и автозагрузка (composer PSR-4 DleApi\).
 */

if(!defined('DATALIFEENGINE')) {
	exit(__('Попытка взлома!'));
}

if(is_file(API_ROOT . '/vendor/autoload.php')) {
	require_once API_ROOT . '/vendor/autoload.php';
}

require_once DLEPlugins::Check(API_ROOT . '/src/Http/V2/Helpers.php');
require_once DLEPlugins::Check(API_ROOT . '/src/Fluent/functions.php');
