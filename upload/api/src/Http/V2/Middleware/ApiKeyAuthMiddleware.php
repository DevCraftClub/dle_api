<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Middleware;

use DevCraft\Core\Application;
use DevCraft\Modules\DleApi\Models\ApiKey;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

/**
 * Authorization: Bearer <raw-api-key> для `GET /key/check`.
 */
final class ApiKeyAuthMiddleware implements MiddlewareInterface {

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		$header = $request->getHeaderLine('Authorization');
		if(!preg_match('/^Bearer\s+(\S+)$/i', $header, $m)) {
			return $this->unauthorized('Требуется Authorization: Bearer <apiKey>');
		}

		/** @var ApiKeyRepository $repo */
		$repo = Application::instance()->database()->repository(ApiKey::class);
		$key  = $repo->findActiveByApi($m[1]);
		if($key === null) {
			return $this->unauthorized('Недействительный или неактивный API-ключ');
		}

		return $handler->handle(
			$request
				->withAttribute('auth_via', 'raw_api_key')
				->withAttribute('oauth_token', [])
				->withAttribute('api_key', ApiKeyRepository::toAuthArray($key))
				->withAttribute('api_key_entity', $key),
		);
	}

	private function unauthorized(string $message): ResponseInterface {
		$response = new Response(401);
		$response->getBody()->write(json_encode([
			'error'   => 'unauthorized',
			'message' => $message,
		], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

		return $response->withHeader('Content-Type', 'application/json; charset=utf-8')
			->withHeader('WWW-Authenticate', 'Bearer');
	}

}
