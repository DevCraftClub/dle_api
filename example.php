<?php

/**
 * Функция подключения к DLE API
 *
 * @author Maxim Harder
 * @link   https://devcraft.club/downloads/dle-api.20/
 *
 * @param    array     $values      Передача переменных для фильтрации, добавления и / или удаления данных
 * @param    string    $method      Метод запроса к API. По умолчанию GET
 *
 * @param    string    $key         Ключ API
 * @param    string    $db_table    Название таблицы данных, а так-же точки подключения к DLE API
 *
 * @return string Возвращает ответ API
 */
function api_connect(string $key, string $db_table, array $values = [], string $method = 'GET') {
	if (!in_array(strtoupper($method), ['GET', 'POST', 'PUT', 'DELETE'])) {
		return json_encode(["status"  => "error",
							"message" => "Выбранный вами метод ({$method}) не поддерживается. Доступные методы: GET, POST, PUT, DELETE"
		], JSON_UNESCAPED_UNICODE);
	}

	$url = "http://ваш-сайт.dev/api/v1/{$db_table}";

	$header = ['x-api-key' => $key,];

	if (strtoupper($method) === 'GET')
		$header = array_merge($header, $values);

	$header_array = [];

	foreach ($header as $name => $val) {
		$header_array[] = $name . ': ' . $val;
	}

	$curl_data = array(
		CURLOPT_URL            => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_CUSTOMREQUEST  => $method,
		CURLOPT_HTTPHEADER     => $header_array,
	);

	if (in_array(strtoupper($method), ['POST', 'PUT'])) $curl_data['CURLOPT_POSTFIELDS'] = urlencode($values);

	$curl = curl_init();

	curl_setopt_array($curl, $curl_data);
	$response = curl_exec($curl);

	curl_close($curl);

	return $response;
}


echo '<pre>' . api_connect('b733557-557d45d-45d6747-747774f-74f63d4-3d422f6-2f62dcf-dcf0d2c', 'users',) . '</pre>';
