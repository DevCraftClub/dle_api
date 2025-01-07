<?php
global $app;
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

$api_name     = 'vote';
$possibleData = array(
	array(
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 9
	),
	array(
		'name'     => 'category',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'vote_num',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	),
	array(
		'name'     => 'date',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 25
	),
	array(
		'name'     => 'title',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 200
	),
	array(
		'name'     => 'body',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'approve',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'start',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 15
	),
	array(
		'name'     => 'end',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 15
	),
	array(
		'name'     => 'grouplevel',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 250
	),
);

$Cruds = new CrudController($api_name, $possibleData);

$app->group("/{$api_name}", function (RouteCollectorProxy $sub) use ($Cruds) {
	$sub->get('[/]', [$Cruds, 'handleGet']);
	$sub->post('[/]', [$Cruds, 'handlePost']);
	$sub->put('/{id}[/]', [$Cruds, 'handlePut']);
	$sub->delete('/{id}[/]', [$Cruds, 'handleDelete']);

	// Own routing Add
});
