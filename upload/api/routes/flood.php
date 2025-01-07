<?php
global $app;
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

$api_name     = "flood";
$possibleData = array(
	array(
		'name'     => 'f_id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 11
	),
	array(
		'name'     => 'ip',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 46
	),
	array(
		'name'     => 'id',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 20
	),
	array(
		'name'     => 'flag',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),

);



$Cruds = new CrudController($api_name, $possibleData, orderBy: 'f_id');

$app->group("/{$api_name}", function (RouteCollectorProxy $sub) use ($Cruds) {
	$sub->get('[/]', [$Cruds, 'handleGet']);
	$sub->post('[/]', [$Cruds, 'handlePost']);
	$sub->put('/{id}[/]', [$Cruds, 'handlePut']);
	$sub->delete('/{id}[/]', [$Cruds, 'handleDelete']);

	// Own routing Add
}
);
