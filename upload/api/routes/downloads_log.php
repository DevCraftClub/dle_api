<?php
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

global $app;

$api_name     = 'downloads_log';
$possibleData = [
	[
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 11
	],
	[
		'name'     => 'user_id',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
	],
	[
		'name'     => 'ip',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 46
	],
	[
		'name'     => 'file_id',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
	],
	[
		'name'     => 'date',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
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
