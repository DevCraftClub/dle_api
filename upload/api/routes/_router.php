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

if (!isset($_SERVER['REDIRECT_URL'])) {
	$redirectUrl = $_SERVER['REQUEST_URI'];
} else {
	$redirectUrl = $_SERVER['REDIRECT_URL'];
}

if(!$redirectUrl) die('Переменная REDIRECT_URL / REQUEST_URI не установлена!');

// Проверяем соответствие URL-адреса маршруту
if (!preg_match('#/api/v1/([^/]+)/#', $redirectUrl, $matches)) {
	die('Некорректный маршрут!');
}

$routeFileName = "{$matches[1]}.php";

// Проверяем запрещённые или отсутствующие маршруты
if (in_array($routeFileName, $forbiddenFiles, true)) {
	die("Данный ({{$matches[1]}) маршрут запрещён!");
}

if (!in_array($routeFileName, $routeFiles, true)) {
	die("Данного маршрут ({{$matches[1]}) не существует!");
}

// Подключаем файл маршрута с проверкой через DLEPlugins
include_once DLEPlugins::Check("{$routePath}/{$routeFileName}");

