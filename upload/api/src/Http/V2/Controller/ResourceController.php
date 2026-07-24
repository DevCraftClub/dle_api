<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Controller;

use DleApi\Http\V2\JsonResponder;
use DleApi\Http\V2\Support\TableBodyHydrator;
use DleApi\Http\V2\FilterBag;
use DleApi\Fluent\TableQuery;
use DleApi\Schema\SchemaRegistry;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

/**
 * Типизированные эндпоинты: post / user / usergroup / plugin / conversations / health.
 */
final class ResourceController {

	public function __construct(
		private readonly TableBodyHydrator $hydrator = new TableBodyHydrator(),
	) {
	}

	public function listPosts(Request $request, Response $_response): Response {
		$columns = array_flip(SchemaRegistry::get('post')->columns());
		$headers = [];
		foreach($request->getHeaders() as $name => $values) {
			$lname = strtolower((string) $name);
			if(!isset($columns[$lname])) {
				continue;
			}
			$headers[$lname] = $values[0] ?? '';
		}
		try {
			$parsed = FilterBag::parse($request->getQueryParams(), $headers);
			$query  = FilterBag::apply(TableQuery::of('post'), $parsed);
			$rows   = $query->fetchAll();
		} catch(Throwable $e) {
			return JsonResponder::error('list_failed', $e->getMessage(), 422);
		}

		foreach($rows as &$row) {
			if(isset($row['category'])) {
				$row['category'] = [
					'raw'    => $row['category'],
					'parsed' => dle_api_parse_categories((string) $row['category']),
				];
			}
			if(isset($row['xfields'])) {
				$row['xfields'] = [
					'raw'    => $row['xfields'],
					'parsed' => dle_api_parse_xfields((string) $row['xfields']),
				];
			}
		}
		unset($row);

		return JsonResponder::ok(['data' => $rows, 'count' => count($rows)]);
	}

	public function getPost(Request $_request, Response $_response, array $args): Response {
		$id  = (int) $args['id'];
		$row = dle_api_find('post', $id);
		if($row === null) {
			return JsonResponder::error('not_found', __('Новость не найдена'), 404);
		}
		$row['category'] = [
			'raw'    => $row['category'] ?? '',
			'parsed' => dle_api_parse_categories((string) ($row['category'] ?? '')),
		];
		$row['xfields'] = [
			'raw'    => $row['xfields'] ?? '',
			'parsed' => dle_api_parse_xfields((string) ($row['xfields'] ?? '')),
		];
		$row['xfields_schema'] = dle_api_xfields_schema();

		return JsonResponder::ok(['data' => $row]);
	}

	public function createPost(Request $request, Response $_response): Response {
		return $this->createTable($request, 'post');
	}

	public function createUser(Request $request, Response $_response): Response {
		return $this->createTable($request, 'users');
	}

	public function createUsergroup(Request $request, Response $_response): Response {
		$body = (array) $request->getParsedBody();
		if(!empty($body['name']) && empty($body['group_name'])) {
			$body['group_name'] = $body['name'];
		}

		return $this->createTableWithBody($body, 'usergroups');
	}

	public function createPlugin(Request $request, Response $_response): Response {
		$body = (array) $request->getParsedBody();
		if(!empty($body['files']) && is_array($body['files']) && empty($body['plugins_files'])) {
			$body['plugins_files'] = array_map(
				static fn($f) => is_string($f) ? ['file' => $f] : $f,
				$body['files'],
			);
			unset($body['files']);
		}

		return $this->createTableWithBody($body, 'plugins');
	}

	public function conversations(Request $request, Response $_response): Response {
		$limit = max(1, min(100, (int) ($request->getQueryParams()['limit'] ?? 20)));
		$table = PREFIX . '_conversations';
		$rows  = dle_api_db()->query(
			"SELECT * FROM {$table} ORDER BY id DESC LIMIT {$limit}"
		)->fetchAll();

		return JsonResponder::ok(['data' => is_array($rows) ? $rows : []]);
	}

	public function health(Request $_request, Response $_response): Response {
		return JsonResponder::ok([
			'version' => '200.1.0',
			'api'     => 'v2',
			'auth'    => 'Bearer',
		]);
	}

	private function createTable(Request $request, string $table): Response {
		return $this->createTableWithBody((array) $request->getParsedBody(), $table);
	}

	/**
	 * @param array<string, mixed> $body
	 */
	private function createTableWithBody(array $body, string $table): Response {
		try {
			$id = $this->hydrator->hydrate($table, $body)->create();
		} catch(Throwable $e) {
			return JsonResponder::error('create_failed', $e->getMessage(), 422);
		}

		return JsonResponder::ok(['id' => $id], 201);
	}

}
