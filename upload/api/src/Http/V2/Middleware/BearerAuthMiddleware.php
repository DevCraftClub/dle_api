<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use DleApi\Http\V2\OAuth\TokenService;

/**
 * Authorization: Bearer &lt;AuthToken&gt; (только access_token).
 */
final class BearerAuthMiddleware implements MiddlewareInterface {

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		$path = $request->getUri()->getPath();
		if(
			str_contains($path, '/oauth/token')
			|| str_contains($path, '/oauth/authorize')
			|| str_contains($path, '/oauth/revoke')
			|| str_contains($path, '/.well-known/')
			|| str_contains($path, '/health')
		) {
			return $handler->handle($request);
		}

		$header = $request->getHeaderLine('Authorization');
		if(!preg_match('/^Bearer\s+(\S+)$/i', $header, $m)) {
			return $this->unauthorized('Требуется Authorization: Bearer <AuthToken>');
		}

		$auth = (new TokenService())->validateAccessToken($m[1]);
		if($auth === null) {
			return $this->unauthorized('Недействительный или просроченный AuthToken');
		}

		return $handler->handle(
			$request
				->withAttribute('auth_via', $auth['auth_via'])
				->withAttribute('oauth_token', $auth['token'])
				->withAttribute('api_key', $auth['key']),
		);
	}

	private function unauthorized(string $message): ResponseInterface {
		$response = new Response(401);
		$response->getBody()->write(json_encode([
			'error'   => 'unauthorized',
			'message' => $message,
		], JSON_UNESCAPED_UNICODE));

		return $response->withHeader('Content-Type', 'application/json; charset=utf-8')
			->withHeader('WWW-Authenticate', 'Bearer');
	}

}
