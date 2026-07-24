<?php

declare(strict_types=1);

namespace DleApi\Http\V2;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

/**
 * JSON-ответы API v2.
 */
final class JsonResponder {

	/**
	 * @param array<string, mixed>|list<mixed> $data
	 */
	public static function ok(array $data, int $status = 200): ResponseInterface {
		$response = new Response($status);
		$response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

		return $response->withHeader('Content-Type', 'application/json; charset=utf-8');
	}

	public static function error(string $error, string $message, int $status = 400, array $details = []): ResponseInterface {
		$payload = ['error' => $error, 'message' => $message];
		if($details !== []) {
			$payload['details'] = $details;
		}

		return self::ok($payload, $status);
	}

}
