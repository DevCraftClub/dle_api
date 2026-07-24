<?php

/**
 * DLE API v2 — точка входа.
 *
 * Требует DevCraft Admin ≥ 200.4.0 (CycleORM).
 * Доступ к ресурсам: Authorization: Bearer <access_token|api_key>.
 */

declare(strict_types=1);

use DI\Container;
use Slim\Factory\AppFactory;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

@error_reporting(E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
@ini_set('error_reporting', (string) (E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE));
@ini_set('display_errors', '1');
@ini_set('html_errors', '0');

const DATALIFEENGINE = true;
define('ROOT_DIR', dirname(__FILE__, 3));
define('API_DIR', __DIR__);
define('API_ROOT', dirname(__DIR__));
define('ENGINE_DIR', ROOT_DIR . '/engine');

include_once API_ROOT . '/vendor/autoload.php';

require_once ENGINE_DIR . '/classes/plugins.class.php'; // предоставляет DLEPlugins::Check
require_once DLEPlugins::Check(ROOT_DIR . '/devcraft/init.php');

if(!defined('DEVCRAFT_BOOTSTRAPPED')) {
	header('Content-Type: application/json; charset=utf-8');
	http_response_code(503);
	echo json_encode([
		'error'   => 'devcraft_required',
		'message' => 'Требуется DevCraft Admin ≥ 200.4.0 (vendor/autoload).',
	], JSON_UNESCAPED_UNICODE);
	exit;
}

if(session_status() === PHP_SESSION_ACTIVE) {
	session_write_close();
}

require_once DLEPlugins::Check(API_ROOT . '/src/Http/V2/Bootstrap.php');

$container = new Container();
AppFactory::setContainer($container);

$container->set('settings', static function () {
	return [
		'displayErrorDetails' => true,
		'logger'              => [
			'name'  => 'dle-api-v2',
			'level' => Level::Warning,
			'path'  => API_ROOT . '/var/logs/v2.log',
		],
	];
});

$container->set('logger', static function ($c) {
	$settings = $c->get('settings')['logger'];
	$logger   = new Logger($settings['name']);
	$logger->pushHandler(new StreamHandler($settings['path'], $settings['level']));

	return $logger;
});

$app = AppFactory::create();
$app->setBasePath('/api/v2');
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(
	new \DleApi\Http\V2\JsonErrorHandler($app->getResponseFactory()),
);

require_once DLEPlugins::Check(API_DIR . '/routes.php');

$app->run();
