<?php

declare(strict_types=1);

namespace DleApi\Http\V2;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\ErrorHandlerInterface;
use Throwable;

/**
 * JSON-ошибки API v2 (без HTML даже при Accept: text/html).
 */
final class JsonErrorHandler implements ErrorHandlerInterface {

	public function __construct(
		private readonly ResponseFactoryInterface $responseFactory,
	) {}

	public function __invoke(
		ServerRequestInterface $request,
		Throwable $exception,
		bool $displayErrorDetails,
		bool $logErrors,
		bool $logErrorDetails,
	): ResponseInterface {
		$status = 500;
		if($exception instanceof HttpException) {
			$code = (int) $exception->getCode();
			if($code >= 400 && $code < 600) {
				$status = $code;
			}
		}

		$error   = 'error';
		$message = $exception->getMessage() !== '' ? $exception->getMessage() : 'Error';
		$body    = [];

		if($exception instanceof HttpMethodNotAllowedException) {
			$error              = 'method_not_allowed';
			$allowed            = $exception->getAllowedMethods();
			$body['allowed']    = $allowed;
			$message            = __('Метод не разрешён') . '. ' . __('Допустимо') . ': ' . implode(', ', $allowed);
		} elseif($exception instanceof HttpNotFoundException) {
			$error   = 'not_found';
			$message = __('Маршрут не найден');
		}

		$payload = array_merge([
			'error'   => $error,
			'message' => $message,
		], $body);

		if($displayErrorDetails && !($exception instanceof HttpException)) {
			$payload['details'] = [
				'type'    => $exception::class,
				'message' => $exception->getMessage(),
			];
		}

		$response = $this->responseFactory->createResponse($status);
		$response->getBody()->write((string) json_encode($payload, JSON_UNESCAPED_UNICODE));

		$response = $response->withHeader('Content-Type', 'application/json; charset=utf-8');
		if($exception instanceof HttpMethodNotAllowedException) {
			$response = $response->withHeader('Allow', implode(', ', $exception->getAllowedMethods()));
		}

		return $response;
	}

}
