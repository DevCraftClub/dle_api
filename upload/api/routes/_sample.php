<?php
if( !defined( 'DATALIFEENGINE' ) ) {
    header( "HTTP/1.1 403 Forbidden" );
    header ( 'Location: ../../' );
    die( "Hacking attempt!" );
}

use Slim\Routing\RouteCollectorProxy;

global $app;

// указывается название таблицы
$api_name = 'xfsearch';

// Массив с данными таблицы
// Дополняем всеми полями, что есть в таблице
// Альтернатива: https://github.com/DevCraftClub/dle_api_crawlerfx
$possibleData = array(
	array(
		'name' => 'DBColumn name',  // Название поля
		'type' => "Type of value",  // integer, string, boolean, double
		'required' => true/false,   // Обязательное поле?
		'post' => true/false,       // Разрешить использовать при добавлении или редактуре?
		'length' => 0,       		// Указывается ограничение для типа string. Содержимое будет обрезаться при нарушении макс. значения
	),
);

// Указываем поля, которые будут указывать на параметр пользователя, с которым API ключ взаимодействует
$own_fields = [
	'name'
];

// Даём возможность добавить дополнительные поля следующим шаблоном:
// $possibleData[] = array(
//                  'name' => 'DBColumn name',
//                  'type' => "Type of value",  // integer, string, boolean, double
//                  'required' => true/false,   // Обязательное поле?
//                  'post' => true/false,       // Разрешить использовать при добавлении или редактуре?
//                  'length' => 0,       // Указывается ограничение для типа string. Содержимое будет обрезаться при нарушении макс. значения
// );
// Оставляем строчку как ориентир
// possibleData Add

// Добавляем класс с контроллером под управление над данными
$Cruds = new CrudController($api_name, $possibleData, $own_fields);

$app->group('/' . $api_name, function (RouteCollectorProxy $subgroup) use ($Cruds) {
	$subgroup->get('[/]', [$Cruds, 'handleGet']);
	$subgroup->post('[/]', [$Cruds, 'handlePost']);
	$subgroup->put('/{id}[/]', [$Cruds, 'handlePut']);
	$subgroup->delete('{id}[/]', [$Cruds, 'handleDelete']);

	// Own routing Add
}
);
