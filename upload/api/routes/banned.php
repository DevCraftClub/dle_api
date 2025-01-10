<?php
global $app;
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

$api_name     = "banned";
$possibleData = array(
	array(
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 6
	),
	array(
		'name'     => 'users_id',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
	),
	array(
		'name'     => 'descr',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'date',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 15
	),
	array(
		'name'     => 'days',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 4
	),
	array(
		'name'     => 'ip',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 46
	),

);

$Cruds = new CrudController($api_name, $possibleData, ['users_id']);

$app->group("/{$api_name}", function (RouteCollectorProxy $sub) use ($Cruds) {
		$sub->get('[/]', [$Cruds, 'handleGet']);
		$sub->get('/{id}[/]', [$Cruds, 'handleGetSingle']);
		$sub->post('[/]', [$Cruds, 'handlePost']);
		$sub->put('/{id}[/]', [$Cruds, 'handlePut']);
		$sub->delete('/{id}[/]', [$Cruds, 'handleDelete']);

		// Own routing Add
	}
);
