<?php
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

global $app;

$api_name     = 'comments_files';
$possibleData = [
	[
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 11
	],
	[
		'name'     => 'c_id',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
	],
	[
		'name'     => 'author',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 40
	],
	[
		'name'     => 'date',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 15
	],
	[
		'name'     => 'name',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	],
	[
		'name'     => 'driver',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	],

];

$own_fields = ['author'];

$Cruds = new CrudController($api_name, $possibleData, $own_fields);

$app->group('/' . $api_name, function (RouteCollectorProxy $subgroup) use ($Cruds) {
	$subgroup->get('[/]', [$Cruds, 'handleGet']);
	$subgroup->get('/{id}[/]', [$Cruds, 'handleGetSingle']);
	$subgroup->post('[/]', [$Cruds, 'handlePost']);
	$subgroup->put('/{id}[/]', [$Cruds, 'handlePut']);
	$subgroup->delete('{id}[/]', [$Cruds, 'handleDelete']);

	// Own routing Add
});