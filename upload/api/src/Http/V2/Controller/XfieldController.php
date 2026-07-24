<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Controller;

use DleApi\Http\V2\JsonResponder;
use DleApi\Xfield\XfieldDefinitionNormalizer;
use DleApi\Xfield\XfieldStore;
use DleApi\Xfield\XfieldTypeSpec;
use DleApi\Xfield\XfieldValidationException;
use DleApi\Xfield\XfieldValueEncoder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

/**
 * Каталог доп. полей /xfields/{scope}.
 */
final class XfieldController {

	public function list(Request $_request, Response $_response, array $args): Response {
		try {
			$store = new XfieldStore((string) $args['scope']);
		} catch(Throwable $e) {
			return JsonResponder::error('invalid_scope', $e->getMessage(), 400);
		}

		return JsonResponder::ok(['data' => $store->read(), 'scope' => $store->scope()]);
	}

	public function encode(Request $request, Response $_response, array $args): Response {
		$body = (array) $request->getParsedBody();
		try {
			$store = new XfieldStore((string) $args['scope']);
			if(isset($body['fields']) && is_array($body['fields'])) {
				$map = $body['fields'];
			} elseif(isset($body['name'])) {
				$map = [(string) $body['name'] => $body['value'] ?? ''];
			} else {
				return JsonResponder::error('validation', __('Нужен fields{} или name+value'), 422);
			}
			$result = XfieldValueEncoder::encodeFromStore($store, $map);
		} catch(XfieldValidationException $e) {
			return JsonResponder::error('validation', $e->getMessage(), 422, $e->details());
		} catch(Throwable $e) {
			return JsonResponder::error('xfield_error', $e->getMessage(), 400);
		}

		return JsonResponder::ok([
			'raw'    => $result['raw'],
			'parsed' => $result['parsed'],
			'scope'  => $store->scope(),
		]);
	}

	public function get(Request $request, Response $_response, array $args): Response {
		try {
			$store = new XfieldStore((string) $args['scope']);
			$field = $store->getField((string) $args['name']);
		} catch(Throwable $e) {
			return JsonResponder::error('xfield_error', $e->getMessage(), 400);
		}
		if($field === null) {
			return JsonResponder::error('not_found', __('Поле не найдено'), 404);
		}
		$as = strtolower((string) ($request->getQueryParams()['as'] ?? ''));
		if($as !== '') {
			$type = (string) ($field['type'] ?? '');
			if($type !== $as) {
				return JsonResponder::error(
					'validation',
					'Тип поля не совпадает',
					422,
					['fields' => ['type' => "Ожидался {$as}, в каталоге «{$type}»"]],
				);
			}
			$field = XfieldTypeSpec::project($field, $as, $store->scope());
		}

		return JsonResponder::ok(['data' => $field, 'scope' => $store->scope()]);
	}

	public function create(Request $request, Response $_response, array $args): Response {
		$body = (array) $request->getParsedBody();
		try {
			$store = new XfieldStore((string) $args['scope']);
			$norm  = new XfieldDefinitionNormalizer($store);
			$def   = $norm->normalize($body, requireUniqueName: true);
			$store->upsertField((string) $def['name'], $def);
		} catch(XfieldValidationException $e) {
			return JsonResponder::error('validation', $e->getMessage(), 422, $e->details());
		} catch(Throwable $e) {
			return JsonResponder::error('xfield_error', $e->getMessage(), 422);
		}

		return JsonResponder::ok(['name' => $def['name'], 'scope' => (string) $args['scope'], 'data' => $def], 201);
	}

	public function put(Request $request, Response $_response, array $args): Response {
		$body         = (array) $request->getParsedBody();
		$name         = (string) $args['name'];
		$body['name'] = $name;
		try {
			$store = new XfieldStore((string) $args['scope']);
			if($store->getField($name) === null) {
				return JsonResponder::error('not_found', __('Поле не найдено'), 404);
			}
			$norm = new XfieldDefinitionNormalizer($store);
			$def  = $norm->normalize($body, requireUniqueName: false);
			$store->upsertField($name, $def);
		} catch(XfieldValidationException $e) {
			return JsonResponder::error('validation', $e->getMessage(), 422, $e->details());
		} catch(Throwable $e) {
			return JsonResponder::error('xfield_error', $e->getMessage(), 422);
		}

		return JsonResponder::ok(['name' => $name, 'updated' => true, 'data' => $def]);
	}

	public function patch(Request $request, Response $_response, array $args): Response {
		$body = (array) $request->getParsedBody();
		$name = (string) $args['name'];
		try {
			$store = new XfieldStore((string) $args['scope']);
			$cur   = $store->getField($name);
			if($cur === null) {
				return JsonResponder::error('not_found', __('Поле не найдено'), 404);
			}
			$norm = new XfieldDefinitionNormalizer($store);
			$def  = $norm->normalize(array_merge($cur, $body, ['name' => $name]), requireUniqueName: false);
			$store->upsertField($name, $def);
		} catch(XfieldValidationException $e) {
			return JsonResponder::error('validation', $e->getMessage(), 422, $e->details());
		} catch(Throwable $e) {
			return JsonResponder::error('xfield_error', $e->getMessage(), 422);
		}

		return JsonResponder::ok(['name' => $name, 'patched' => true, 'data' => $def]);
	}

	public function delete(Request $_request, Response $_response, array $args): Response {
		$name = (string) $args['name'];
		try {
			$store = new XfieldStore((string) $args['scope']);
			if($store->getField($name) === null) {
				return JsonResponder::error('not_found', __('Поле не найдено'), 404);
			}
			$store->deleteField($name);
		} catch(Throwable $e) {
			return JsonResponder::error('xfield_error', $e->getMessage(), 422);
		}

		return JsonResponder::ok(['name' => $name, 'deleted' => true]);
	}

}
