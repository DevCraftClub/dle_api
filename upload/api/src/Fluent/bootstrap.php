<?php

declare(strict_types=1);

/**
 * Глобальные fluent-функции (in-process).
 *
 * require_once DLEPlugins::Check(ROOT_DIR . '/api/src/Fluent/bootstrap.php');
 */

$apiRoot = dirname(__DIR__, 2);

if(is_file($apiRoot . '/vendor/autoload.php')) {
	require_once $apiRoot . '/vendor/autoload.php';
} else {
	spl_autoload_register(static function (string $class) use ($apiRoot): void {
		if(!str_starts_with($class, 'DleApi\\')) {
			return;
		}
		$file = $apiRoot . '/src/' . str_replace('\\', '/', substr($class, strlen('DleApi\\'))) . '.php';
		if(is_file($file)) {
			require_once (class_exists('DLEPlugins', false) ? DLEPlugins::Check($file) : $file);
		}
	});
}

require_once (class_exists('DLEPlugins', false)
	? DLEPlugins::Check($apiRoot . '/src/Fluent/DcDatabase.php')
	: $apiRoot . '/src/Fluent/DcDatabase.php');
require_once (class_exists('DLEPlugins', false)
	? DLEPlugins::Check(__DIR__ . '/functions.php')
	: __DIR__ . '/functions.php');

use DleApi\Fluent\TableBuilder;
use DleApi\Fluent\TableQuery;
use function DleApi\Fluent\prepare as _prepare;
use function DleApi\Fluent\query as _query;

if(!function_exists('prepare')) {
	function prepare(string $table): TableBuilder {
		return _prepare($table);
	}
}

if(!function_exists('query')) {
	function query(string $table): TableQuery {
		return _query($table);
	}
}
