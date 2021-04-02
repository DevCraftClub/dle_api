<?php

/**
 * @author Maxim Harder <dev@devcraft.club>
 * @copyright 2019-2021 DevCraft.club
 *
 * Приложение должно заменить стандартный API самого DLE и предоставить альтернативный вариант.
 * Использовать на свой страх и риск!
 *
 * @uses functions.php
 * @uses routes/****.php
 * @filesource index.php
 */

@error_reporting(E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
@ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
@ini_set('display_errors', true);
@ini_set('html_errors', false);

//Даём понять DLE, что приложение не чужое и может использовать файлы движка
define('DATALIFEENGINE', true);
define('ROOT_DIR', dirname(__FILE__, 2));
define('API_DIR', __DIR__);
define('ENGINE_DIR', ROOT_DIR.'/engine');

//Подключаем функционал API и самой DLE
require_once ENGINE_DIR.'/classes/plugins.class.php';

include_once DLEPlugins::Check(API_DIR.'/includes/functions.php');

$config = [
	'settings' => [
		'displayErrorDetails' => true,
		'debug' => true,
		'logger' => [
			'name' => 'dle-api',
			'level' => Monolog\Logger::DEBUG,
			'path' => API_DIR.'/logs/app.log',
		],
	],
];

// Подключаем файл-роутер и запускаем главную функцию
include_once DLEPlugins::Check(API_DIR.'/routes/_router.php');

?>
