<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Controller;

use DleApi\Fluent\TableQuery;
use DleApi\Http\V2\Auth\SecureFieldMasker;
use DleApi\Http\V2\Auth\TableScopeGuard;
use DleApi\Http\V2\FilterBag;
use DleApi\Http\V2\JsonResponder;
use DleApi\Http\V2\Support\TableBodyHydrator;
use DleApi\Http\V2\Table\HttpTableSchemaResolver;
use DleApi\Schema\IntrospectedTableSchema;
use DleApi\Sdk\SdkException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

/**
 * CRUD для HTTP /table/{name}.
 */
final class TableCrudController {

	public function __construct(
		private readonly HttpTableSchemaResolver $resolver = new HttpTableSchemaResolver(),
		private readonly TableScopeGuard $scope = new TableScopeGuard(),
		private readonly TableBodyHydrator $hydrator = new TableBodyHydrator(),
		private readonly SecureFieldMasker $masker = new SecureFieldMasker(),
	) {
	}

	public function list(Request $request, Response $_response, array $args): Response {
		$name   = (string) $args['name'];
		$apiKey = (array) $request->getAttribute('api_key');
		$schema = $this->resolver->resolve($name);
		if($schema === null) {
			return JsonResponder::error('unknown_table', __('Неизвестная таблица') . ": {$name}", 404);
		}
		try {
			$this->scope->assert($apiKey, $name, 'read');
		} catch(SdkException $e) {
			return JsonResponder::error($e->errorCode(), $e->getMessage(), $e->httpStatus());
		}
		try {
			$parsed = FilterBag::parse($request->getQueryParams());
			$query  = FilterBag::apply(TableQuery::ofSchema($schema), $parsed);
			$rows   = $query->fetchAll();
		} catch(Throwable $e) {
			return JsonResponder::error('list_failed', $e->getMessage(), 422);
		}
		/** @var list<array<string, mixed>> $rows */
		$masked = $this->masker->mask($apiKey, $rows);

		return JsonResponder::ok(['data' => $masked, 'count' => count($rows), 'table' => $name]);
	}

	public function get(Request $request, Response $_response, array $args): Response {
		$name   = (string) $args['name'];
		$id     = $args['id'];
		$apiKey = (array) $request->getAttribute('api_key');
		$schema = $this->resolver->resolve($name);
		if($schema === null) {
			return JsonResponder::error('unknown_table', __('Неизвестная таблица') . ": {$name}", 404);
		}
		try {
			$this->scope->assert($apiKey, $name, 'read');
		} catch(SdkException $e) {
			return JsonResponder::error($e->errorCode(), $e->getMessage(), $e->httpStatus());
		}
		try {
			$row = TableQuery::ofSchema($schema)->find($id);
		} catch(Throwable $e) {
			return JsonResponder::error('unsupported_pk', $e->getMessage(), 400);
		}
		if($row === null) {
			return JsonResponder::error('not_found', __('Запись не найдена'), 404);
		}
		try {
			$this->masker->assertOwnOnlyRow($apiKey, $row, $name);
		} catch(SdkException $e) {
			return JsonResponder::error($e->errorCode(), $e->getMessage(), $e->httpStatus());
		}

		return JsonResponder::ok(['data' => $this->masker->mask($apiKey, $row), 'table' => $name]);
	}

	public function create(Request $request, Response $_response, array $args): Response {
		$name   = (string) $args['name'];
		$apiKey = (array) $request->getAttribute('api_key');
		$schema = $this->resolver->resolve($name);
		if($schema === null) {
			return JsonResponder::error('unknown_table', __('Неизвестная таблица') . ": {$name}", 404);
		}
		try {
			$this->scope->assert($apiKey, $name, 'write');
		} catch(SdkException $e) {
			return JsonResponder::error($e->errorCode(), $e->getMessage(), $e->httpStatus());
		}
		$body = (array) $request->getParsedBody();
		try {
			if($schema instanceof IntrospectedTableSchema) {
				$pk  = $schema->primaryKey();
				$pkc = is_string($pk) ? $pk : 'id';
				$id  = $schema->withAttributes($body)->create()->asArray()[$pkc] ?? 0;
			} else {
				$id = $this->hydrator->hydrate($name, $body)->create();
			}
		} catch(Throwable $e) {
			return JsonResponder::error('create_failed', $e->getMessage(), 422);
		}

		return JsonResponder::ok(['id' => $id, 'table' => $name], 201);
	}

	public function update(Request $request, Response $_response, array $args): Response {
		$name   = (string) $args['name'];
		$id     = $args['id'];
		$apiKey = (array) $request->getAttribute('api_key');
		$schema = $this->resolver->resolve($name);
		if($schema === null) {
			return JsonResponder::error('unknown_table', __('Неизвестная таблица') . ": {$name}", 404);
		}
		try {
			$this->scope->assert($apiKey, $name, 'edit');
		} catch(SdkException $e) {
			return JsonResponder::error($e->errorCode(), $e->getMessage(), $e->httpStatus());
		}
		$body = (array) $request->getParsedBody();
		try {
			$row = dle_api_find($name, $id);
			if($row === null) {
				return JsonResponder::error('not_found', __('Запись не найдена'), 404);
			}
			$this->masker->assertOwnOnlyRow($apiKey, $row, $name);
			dle_api_update_by_pk($name, $id, $body);
		} catch(SdkException $e) {
			return JsonResponder::error($e->errorCode(), $e->getMessage(), $e->httpStatus());
		} catch(Throwable $e) {
			$code = str_contains($e->getMessage(), 'составной PK') ? 400 : 422;

			return JsonResponder::error('update_failed', $e->getMessage(), $code);
		}

		return JsonResponder::ok(['id' => $id, 'table' => $name, 'updated' => true]);
	}

	public function delete(Request $request, Response $_response, array $args): Response {
		$name   = (string) $args['name'];
		$id     = $args['id'];
		$apiKey = (array) $request->getAttribute('api_key');
		$schema = $this->resolver->resolve($name);
		if($schema === null) {
			return JsonResponder::error('unknown_table', __('Неизвестная таблица') . ": {$name}", 404);
		}
		try {
			$this->scope->assert($apiKey, $name, 'delete');
		} catch(SdkException $e) {
			return JsonResponder::error($e->errorCode(), $e->getMessage(), $e->httpStatus());
		}
		try {
			$row = dle_api_find($name, $id);
			if($row === null) {
				return JsonResponder::error('not_found', __('Запись не найдена'), 404);
			}
			$this->masker->assertOwnOnlyRow($apiKey, $row, $name);
			dle_api_delete_by_pk($name, $id);
		} catch(SdkException $e) {
			return JsonResponder::error($e->errorCode(), $e->getMessage(), $e->httpStatus());
		} catch(Throwable $e) {
			$code = str_contains($e->getMessage(), 'составной PK') ? 400 : 422;

			return JsonResponder::error('delete_failed', $e->getMessage(), $code);
		}

		return JsonResponder::ok(['id' => $id, 'table' => $name, 'deleted' => true]);
	}

}
