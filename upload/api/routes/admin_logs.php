<?php

global $app;
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

$api_name = "admin_logs";

$possibleData = [
	[
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 11
	],
	[
		'name'     => 'name',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 40
	],
	[
		'name'     => 'date',
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
		'name'     => 'action',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
	],
	[
		'name'     => 'extras',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	],

];

$own_fields = [
	'name'
];

$Cruds = new CrudController($api_name, $possibleData, $own_fields);

$app->group("/{$api_name}", function (RouteCollectorProxy $sub) use ($Cruds) {

	$sub->get('[/]', [$Cruds, 'handleGet']);
	$sub->get('/{id}[/]', [$Cruds, 'handleGetSingle']);
	$sub->post('[/]', [$Cruds, 'handlePost']);
	$sub->put('/{id}[/]', [$Cruds, 'handlePut']);
	$sub->delete('/{id}[/]', [$Cruds, 'handleDelete']);

	// Own routing Add
});
