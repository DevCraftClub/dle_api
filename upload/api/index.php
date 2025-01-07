<?php

/**
 * @author     Maxim Harder <dev@devcraft.club>
 * @copyright  2019-2021 DevCraft.club
 *
 * Приложение должно заменить стандартный API самого DLE и предоставить альтернативный вариант.
 * Использовать на свой страх и риск!
 *
 * @filesource index.php
 * @uses       routes/****.php
 * @uses       functions.php
 */

use DI\Container;
use Monolog\Level;
use Monolog\Logger;
use Slim\Factory\AppFactory;
use Monolog\Handler\StreamHandler;

@error_reporting(E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
@ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
@ini_set('display_errors', true);
@ini_set('html_errors', false);

//Даём понять DLE, что приложение не чужое и может использовать файлы движка
const DATALIFEENGINE = true;
define('ROOT_DIR', dirname(__FILE__, 2));
const API_DIR    = __DIR__;
const ENGINE_DIR = ROOT_DIR . '/engine';

include_once(API_DIR . '/vendor/autoload.php');

//Подключаем функционал API и самой DLE
require_once ENGINE_DIR . '/classes/plugins.class.php';
include_once DLEPlugins::Check(API_DIR . '/includes/functions.php');
include_once DLEPlugins::Check(API_DIR . '/includes/CacheSystem.php');
include_once DLEPlugins::Check(API_DIR . '/includes/CrudController.php');

$container = new Container();
AppFactory::setContainer($container);

// Настройки контейнера
$container->set('settings', function () {
	return [
		'displayErrorDetails' => true,
		'debug' => true,
		'logger' => [
			'name' => 'dle-api',
			'level' => Level::Warning,
			'path' => API_DIR . '/logs/app.log',
		],
	];
});

// Настраиваем логгер
$container->set('logger', function ($c) {
	$settings = $c->get('settings')['logger'];
	$logger = new Logger($settings['name']);
	$file_handler = new StreamHandler($settings['path'], $settings['level']);
	$logger->pushHandler($file_handler);
	return $logger;
});

// Создаем приложение
$app = AppFactory::create();

$app->setBasePath('/api/v1');

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// Middleware для обработки ошибок
$app->addErrorMiddleware(
	$container->get('settings')['displayErrorDetails'],
	true,
	true
);


// Подключаем файл-роутер и запускаем главную функцию
include_once DLEPlugins::Check(API_DIR . '/routes/_router.php');

$app->run();
