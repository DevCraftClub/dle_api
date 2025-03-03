<?php
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

global $app;

$api_name     = 'redirects';
$possibleData = [
	[
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 11
	],
	[
		'name'     => 'from',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 250
	],
	[
		'name'     => 'to',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 250
	],
	[
		'name'     => 'enabled',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],

];

$own_fields = [];

$Cruds = new CrudController($api_name, $possibleData, $own_fields);

$app->group('/' . $api_name, function (RouteCollectorProxy $subgroup) use ($Cruds) {
	$subgroup->get('[/]', [$Cruds, 'handleGet']);
	$subgroup->get('/{id}[/]', [$Cruds, 'handleGetSingle']);
	$subgroup->post('[/]', [$Cruds, 'handlePost']);
	$subgroup->put('/{id}[/]', [$Cruds, 'handlePut']);
	$subgroup->delete('{id}[/]', [$Cruds, 'handleDelete']);

	// Own routing Add
});