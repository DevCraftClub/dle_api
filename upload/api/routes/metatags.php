<?php
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

global $app;

$api_name     = 'metatags';
$possibleData = [
	[
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 11
	],
	[
		'name'     => 'url',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 250
	],
	[
		'name'     => 'title',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 200
	],
	[
		'name'     => 'description',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 300
	],
	[
		'name'     => 'keywords',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	],
	[
		'name'     => 'page_title',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	],
	[
		'name'     => 'page_description',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	],
	[
		'name'     => 'robots',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
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