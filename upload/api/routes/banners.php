<?php
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

global $app;

$api_name     = 'banners';
$possibleData = [
	[
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 6
	],
	[
		'name'     => 'banner_tag',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 40
	],
	[
		'name'     => 'descr',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 200
	],
	[
		'name'     => 'code',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	],
	[
		'name'     => 'approve',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'short_place',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'bstick',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'main',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'category',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	],
	[
		'name'     => 'grouplevel',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 100
	],
	[
		'name'     => 'start',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 15
	],
	[
		'name'     => 'end',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 15
	],
	[
		'name'     => 'fpage',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'innews',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'devicelevel',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 10
	],
	[
		'name'     => 'allow_views',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'max_views',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
	],
	[
		'name'     => 'allow_counts',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'max_counts',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
	],
	[
		'name'     => 'views',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
	],
	[
		'name'     => 'clicks',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
	],
	[
		'name'     => 'rubric',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	],
	[
		'name'     => 'comments_place',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],

];

$own_fields = [];

// $possibleData[] = array(
//                  'name' => 'DBColumn name',
//                  'type' => "Type of value",  // integer, string, boolean, double
//                  'required' => true/false,   // Обязательное поле?
//                  'post' => true/false,       // Разрешить использовать при добавлении или редактуре?
//                  'length' => 0,       // Указывается ограничение для типа string. Содержимое будет обрезаться при нарушении макс. значения
// );
// possibleData Add

$Cruds = new CrudController($api_name, $possibleData, $own_fields);

$app->group('/' . $api_name, function (RouteCollectorProxy $subgroup) use ($Cruds) {
	$subgroup->get('[/]', [$Cruds, 'handleGet']);
	$subgroup->post('[/]', [$Cruds, 'handlePost']);
	$subgroup->put('/{id}[/]', [$Cruds, 'handlePut']);
	$subgroup->delete('{id}[/]', [$Cruds, 'handleDelete']);

	// Own routing Add
});
