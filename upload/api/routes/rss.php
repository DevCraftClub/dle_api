<?php
global $app;
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

$api_name     = "rss";
$possibleData = array(
	array(
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 6
	),
	array(
		'name'     => 'url',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	),
	array(
		'name'     => 'description',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'allow_main',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_rating',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_comm',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'text_type',
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
		'length'   => 1
	),
	array(
		'name'     => 'search',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'max_news',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 3
	),
	array(
		'name'     => 'cookie',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'category',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'lastdate',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
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
});
