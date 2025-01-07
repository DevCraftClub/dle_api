<?php
global $app;
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

$api_name     = "plugins_files";
$possibleData = array(
	array(
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 11
	),
	array(
		'name'     => 'plugin_id',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
	),
	array(
		'name'     => 'file',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	),
	array(
		'name'     => 'action',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 10
	),
	array(
		'name'     => 'searchcode',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'replacecode',
		'type'     => 'mediumtext',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'active',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'searchcount',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'replacecount',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'filedisable',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'filedleversion',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 10
	),
	array(
		'name'     => 'fileversioncompare',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 2
	),
);

$Cruds = new CrudController($api_name, $possibleData);

$app->group("/{$api_name}", function (RouteCollectorProxy $sub) use ($Cruds) {
	$sub->get('[/]', [$Cruds, 'handleGet']);
	$sub->post('[/]', [$Cruds, 'handlePost']);
	$sub->put('/{id}[/]', [$Cruds, 'handlePut']);
	$sub->delete('/{id}[/]', [$Cruds, 'handleDelete']);

	// Own routing Add
}
);
