<?php

global $app;

if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

$routePath = API_DIR . '/routes';
$forbiddenFiles = ['_router.php', '_sample.php', '.', '..'];
$routeFiles = array_diff(scandir($routePath), $forbiddenFiles);

// Проверяем соответствие URL-адреса маршруту
if (!preg_match('#/api/v1/([^/]+)/#', $_SERVER['REDIRECT_URL'], $matches)) {
	die('Некорректный рутинг!');
}

$routeFileName = "{$matches[1]}.php";

// Проверяем запрещённые или отсутствующие маршруты
if (in_array($routeFileName, $forbiddenFiles, true)) {
	die('Данный рутинг запрещён!');
}

if (!in_array($routeFileName, $routeFiles, true)) {
	die('Данного рутинга не существует!');
}

// Подключаем файл маршрута с проверкой через DLEPlugins
include_once DLEPlugins::Check("{$routePath}/{$routeFileName}");

