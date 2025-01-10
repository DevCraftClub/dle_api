<?php
global $app, $connect;
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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

$app->group("/{$api_name}", function (RouteCollectorProxy $sub) use ($Cruds, $api_name, $connect) {
	$sub->get('[/]', [$Cruds, 'handleGet']);

	$sub->get('/{id}[/]', function (ServerRequestInterface $request, ResponseInterface $response, array $args) use ($Cruds, $api_name, $connect) {
		global $DLEprefix;
		$id = filter_var($args['id'], FILTER_VALIDATE_INT);

		if (!$id) {
			return ErrorResponse::error($response, 400, 'Не указан ID маршрутизации!');
		}

		$header  = $Cruds->parseHeader($request);
		$params  = $request->getQueryParams() ?: [];
		$api_key = $Cruds->extractApiKey($params, $header);

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) {
			return ErrorResponse::error($response, 405, $checkAccess['error']);

		}

		$access = [];

		$access['full']     = $checkAccess['admin'];
		$access['can_read'] = $checkAccess['read'];
		$access['own_only'] = $checkAccess['own'];

		if (!$access['full'] || !$access['can_read']) {
			return ErrorResponse::error($response, 403, 'Недостаточно прав доступа!');
		}

		$sql = "SELECT p.*, e.* FROM {$DLEprefix}_post p LEFT JOIN {$DLEprefix}_post_extras e ON p.id = e.news_id WHERE p.id = :id";
		$sql_params = [
			'id' => $id
		];

		if ($access['own_only']['access'] && !$access['full']) {
			$sql .= " AND autor = :user";
			$sql_params['autor'] = $access['own_only']['user_name'];
		}

		$getData = new CacheSystem($api_name, "{$sql} {$id}");
		if (check_response($getData->get())) {
			$data = $connect::selectOne($sql, $sql_params);

			$data['xfields'] = [
				'raw' => $data['xfields'],
				'parsed' => parseXfields($data['xfields'])
			];

			$data['category'] = [
				'raw' => $data['category'],
				'parsed' => parseCategories($data['category'])
			];

			$getData->setData($data);
			$data = $getData->create();
		} else {
			$data = $getData->get();
		}

		return ErrorResponse::success($response, $data, 200);
	});
	$sub->post('[/]', [$Cruds, 'handlePost']);
	$sub->put('/{id}[/]', [$Cruds, 'handlePut']);
	$sub->delete('/{id}[/]', [$Cruds, 'handleDelete']);

	// Own routing Add
});
