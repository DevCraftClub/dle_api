<?php
global $app;
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

$api_name     = 'vote_result';
$possibleData = array(
	array(
		'name'     => 'id',
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
		'name'     => 'name',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 40
	),
	array(
		'name'     => 'vote_id',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	),
	array(
		'name'     => 'answer',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 3
	),
);

$Cruds = new CrudController($api_name, $possibleData, ['name']);

$app->group("/{$api_name}", function (RouteCollectorProxy $sub) use ($Cruds) {
	$sub->get('[/]', [$Cruds, 'handleGet']);
	$sub->post('[/]', [$Cruds, 'handlePost']);
	$sub->put('/{id}[/]', [$Cruds, 'handlePut']);
	$sub->delete('/{id}[/]', [$Cruds, 'handleDelete']);

	// Own routing Add
});
