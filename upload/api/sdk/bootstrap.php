<?php

declare(strict_types=1);

/**
 * Bootstrap in-process SDK: Fluent + DcApi.
 *
 * require_once DLEPlugins::Check(ROOT_DIR . '/api/sdk/bootstrap.php');
 */

$apiRoot = dirname(__DIR__);

$fluentBootstrap = $apiRoot . '/src/Fluent/bootstrap.php';
require_once (class_exists('DLEPlugins', false)
	? DLEPlugins::Check($fluentBootstrap)
	: $fluentBootstrap);

if(!class_exists('DcApi', false)) {
	$dcApi = __DIR__ . '/DcApi.php';
	require_once (class_exists('DLEPlugins', false)
		? DLEPlugins::Check($dcApi)
		: $dcApi);
}
