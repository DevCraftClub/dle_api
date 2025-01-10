<?php
global $app;
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;

$api_name     = "static";
$possibleData = array(
	array(
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 9
	),
	array(
		'name'     => 'name',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 100
	),
	array(
		'name'     => 'descr',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	),
	array(
		'name'     => 'template',
		'type'     => 'mediumtext',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'allow_br',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_template',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'grouplevel',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 100
	),
	array(
		'name'     => 'tpl',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	),
	array(
		'name'     => 'metadescr',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 300
	),
	array(
		'name'     => 'metakeys',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'views',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	),
	array(
		'name'     => 'template_folder',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 50
	),
	array(
		'name'     => 'date',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 11
	),
	array(
		'name'     => 'metatitle',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	),
	array(
		'name'     => 'allow_count',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'sitemap',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'disable_index',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'disable_search',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'password',
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
});
