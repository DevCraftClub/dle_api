<?php

use Psr\Http\Message\ResponseInterface;


abstract class ErrorResponse {
	public static array $error_types
		= [
			200 => "Успешный ответ",
			201 => "Запись успешно создана / обновлена",
			204 => "Запись успешно удалена",
			400 => "При создании / обновлении записи возникла ошибка",
			401 => "Неверный API-ключ или отсутствие прав доступа",
			404 => "Указанная запись не найдена",
		];

	public static function error(ResponseInterface $response, int $errorNumber, ?string $errorMessage = null): ResponseInterface {
		$response->getBody()->write(
			json_encode(
				[
					"error"       => !in_array($errorNumber, [200, 201, 204]),
					"status"      => $errorNumber,
					"description" => ErrorResponse::$error_types[$errorNumber],
					"message"     => $errorMessage ?? errorResponse::$error_types[$errorNumber]
				], JSON_UNESCAPED_UNICODE
			)
		);

		return $response->withStatus($errorNumber)->withHeader('Content-Type', 'application/json; charset=UTF-8');
	}

	public static function success(ResponseInterface $response, mixed $data = null, int $status = 201): ResponseInterface {
		$response->getBody()->write($data);
		return $response->withStatus($status)->withHeader('Content-Type', 'application/json; charset=UTF-8');
	}

}