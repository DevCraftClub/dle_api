<?php
global $app;
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

$api_name     = "poll";
$possibleData = array(
	array(
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 9
	),
	array(
		'name'     => 'news_id',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
	),
	array(
		'name'     => 'title',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 200
	),
	array(
		'name'     => 'frage',
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
		'name'     => 'votes',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	),
	array(
		'name'     => 'multiple',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'answer',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
);

$Cruds = new CrudController($api_name, $possibleData);

$app->group("/{$api_name}", function (RouteCollectorProxy $sub) use ($Cruds) {
	$sub->get('[/]', [$Cruds, 'handleGet']);
	$sub->get('/{id}[/]', [$Cruds, 'handleGetSingle']);
	$sub->post('[/]', [$Cruds, 'handlePost']);
	$sub->put('/{id}[/]', [$Cruds, 'handlePut']);
	$sub->delete('/{id}[/]', [$Cruds, 'handleDelete']);

	// Own routing Add
}
);
