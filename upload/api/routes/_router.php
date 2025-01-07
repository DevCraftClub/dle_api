<?php

global $app;

if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

$routePath  = API_DIR . '/routes';
$routeFiles = glob($routePath . '/*.php');

$route_path = str_replace([$_SERVER['REQUEST_SCHEME'] . '://', $_SERVER['SERVER_NAME'], 'api', 'v1', '/'], '', $_SERVER['REDIRECT_URL']);
$forbidden = ['_router.php', '_sample.php'];

if(in_array("{$route_path}.php", $forbidden)) die('Данный рутинг запрещён!');
if(!in_array("{$routePath}/{$route_path}.php", $routeFiles)) die('Данного рутинга не существует!');

include_once DLEPlugins::Check("{$routePath}/{$route_path}.php");

