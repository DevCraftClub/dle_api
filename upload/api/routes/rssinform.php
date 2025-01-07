<?php
global $app;
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

$api_name     = "rssinform";
$possibleData = array(
	array(
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 6
	),
	array(
		'name'     => 'tag',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 40
	),
	array(
		'name'     => 'descr',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	),
	array(
		'name'     => 'category',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 200
	),
	array(
		'name'     => 'url',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	),
	array(
		'name'     => 'template',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 40
	),
	array(
		'name'     => 'news_max',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'tmax',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'dmax',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'approve',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'rss_date_format',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 20
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
