<?php
global $app;
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

$api_name     = "twofactor";
$possibleData = array(
	array(
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 11
	),
	array(
		'name'     => 'user_id',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
	),
	array(
		'name'     => 'pin',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 10
	),
	array(
		'name'     => 'attempt',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'date',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
	),
);

$Cruds = new CrudController($api_name, $possibleData, ['user_id']);

$app->group("/{$api_name}", function (RouteCollectorProxy $sub) use ($Cruds) {
	$sub->get('[/]', [$Cruds, 'handleGet']);
	$sub->get('/{id}[/]', [$Cruds, 'handleGetSingle']);
	$sub->post('[/]', [$Cruds, 'handlePost']);
	$sub->put('/{id}[/]', [$Cruds, 'handlePut']);
	$sub->delete('/{id}[/]', [$Cruds, 'handleDelete']);

	// Own routing Add
});
