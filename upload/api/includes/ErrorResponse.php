<?php

use Psr\Http\Message\ResponseInterface;


abstract class Response {
	public static array $error_types
		= [
			200 => "Успешный ответ",
			201 => "Запись успешно создана / обновлена",
			204 => "Запись успешно удалена",
			400 => "При создании / обновлении записи возникла ошибка",
			401 => "Неверный API-ключ или отсутствие прав доступа",
			404 => "Указанная запись не найдена",
		];

	public static function getErrorDescr(int $status) {
		return self::$error_types[$status];
	}

	public static function response(ResponseInterface $response, int $errorNumber, ?string $errorMessage = null): ResponseInterface {
	}

	public static function success(ResponseInterface $response, mixed $data = null, int $status = 201): ResponseInterface {
		$response->getBody()->write($data);
		return $response->withStatus($status)->withHeader('Content-Type', 'application/json; charset=UTF-8');
	}
}

abstract class ErrorResponse extends Response {

	public static function response(ResponseInterface $response, int $errorNumber, ?string $errorMessage = null): ResponseInterface {
		$response->getBody()->write(
			json_encode(
				[
					"error"       => !in_array($errorNumber, [200, 201, 204]),
					"status"      => $errorNumber,
					"description" => self::getErrorDescr($errorNumber),
					"message"     => $errorMessage ?? self::getErrorDescr($errorNumber)
				], JSON_UNESCAPED_UNICODE
			)
		);

		return $response->withStatus($errorNumber)->withHeader('Content-Type', 'application/json; charset=UTF-8');
	}

}