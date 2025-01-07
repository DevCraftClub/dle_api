<?php
global $app;
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$api_name     = "post";
$possibleData = [
	[
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 11
	],
	[
		'name'     => 'autor',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 40
	],
	[
		'name'     => 'date',
		'type'     => 'datetime',
		'required' => true,
		'post'     => true,
		'length'   => 0
	],
	[
		'name'     => 'short_story',
		'type'     => 'mediumtext',
		'required' => true,
		'post'     => true,
		'length'   => 0
	],
	[
		'name'     => 'full_story',
		'type'     => 'mediumtext',
		'required' => true,
		'post'     => true,
		'length'   => 0
	],
	[
		'name'     => 'xfields',
		'type'     => 'mediumtext',
		'required' => true,
		'post'     => true,
		'length'   => 0
	],
	[
		'name'     => 'title',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	],
	[
		'name'     => 'descr',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 300
	],
	[
		'name'     => 'keywords',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	],
	[
		'name'     => 'category',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 190
	],
	[
		'name'     => 'alt_name',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 190
	],
	[
		'name'     => 'comm_num',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	],
	[
		'name'     => 'allow_comm',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'allow_main',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'approve',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'fixed',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'allow_br',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'symbol',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 3
	],
	[
		'name'     => 'tags',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	],
	[
		'name'     => 'metatitle',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	],
];

$Cruds = new CrudController($api_name, $possibleData, ['autor']);

$app->group("/{$api_name}", function (RouteCollectorProxy $sub) use ($Cruds) {
	$sub->get('[/]', [$Cruds, 'handleGet']);
	$sub->post('[/]', [$Cruds, 'handlePost']);
	$sub->put('/{id}[/]', [$Cruds, 'handlePut']);
	$sub->delete('/{id}[/]', [$Cruds, 'handleDelete']);

	// Own routing Add
});
