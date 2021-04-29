<?php
if( !defined( 'DATALIFEENGINE' ) ) {
    header( "HTTP/1.1 403 Forbidden" );
    header ( 'Location: ../../' );
    die( "Hacking attempt!" );
}

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$api_name = "banners_logs";
$possibleData = array(
	array(
		'name' => 'id',
		'type' => 'integer',
		'required' => false,
		'post' => false,
		'length' => 0
	),
	array(
		'name' => 'bid',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'click',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'ip',
		'type' => 'string',
		'required' => false,
		'post' => true,
		'length' => 46
	),
);

// possibleData
// $possibleData[] = array(
//                  'name' => 'DBColumn name',
//                  'type' => "Type of value",
//                  'required' => true/false,   // Обязательное поле?
//                  'post' => true/false,       // Разрешить использовать при добавлении или редактуре?
//                  'length' => 0,				// Указывается ограничение для типа string. Содержимое будет обрезаться при нарушении макс. значения
// );
// possibleData Add

$app->group('/' . $api_name, function ( ) use ( $connect, $api_name, $possibleData ) {
	$header = array();
	$access = array(
		'full' => false,
		'can_read' => false,
		'can_write' => false,
		'can_delete' => false,
	);

	$this->get('[/]', function (Request $request, Response $response, Array $args) use ($possibleData, $api_name, $connect, $header, $access ) {
		foreach ( $request->getHeaders() as $name => $value ) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$params = [];
		foreach( $request->getQueryParams() as $name => $value ) $params[$name] = $value;

		$api_key = $params['x-api-key'] ?? $header['x_api_key'];
		$order_by = $params['orderby'] ?? $header['orderby'];
		$sort = $params['sort'] ?? $header['sort'];
		$limit = $params['limit'] ?? $header['limit'];

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error']), JSON_UNESCAPED_UNICODE));

		$access['full'] = $checkAccess['admin'];
		$access['can_read'] = $checkAccess['read'];
		$access['own_only'] = $checkAccess['own'];

		if ($access['full'] || $access['can_read']) {
			$orderBy = $order_by ?: 'id';
			$sort = $sort ?: 'DESC';
			$limit = $limit ? 'LIMIT '.(int) $limit : '';

			$possibleParams = '';

			foreach ( $header as $data => $value) {
				$keyData = array_search($data, array_column($possibleData, 'name'));
				if ($keyData !== false) {
					$postData = $possibleData[$keyData];
					if ( strlen( $possibleParams ) === 0 ) $possibleParams .= " WHERE {$data}" . getComparer( $header[$data], $postData['type'] );
					else $possibleParams .= " AND {$data}" . getComparer( $header[$data], $postData['type'] );
				}
			}

			$sql = 'SELECT * FROM '. PREFIX . "_{$api_name} {$possibleParams} ORDER by {$orderBy} {$sort} {$limit}";

			$getData = new CacheSystem($api_name, $sql);
			if (empty($getData->get())) {
				$data = $connect->query($sql);
				$getData->setData($data);
				$data = $getData->create();
			} else {
				$data = $getData->get();
			}

			$response->withStatus( 200 )->getBody()->write( $data );

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на просмотр данных!'), JSON_UNESCAPED_UNICODE));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	$this->post('[/]', function (Request $request, Response $response, Array $args) use ($possibleData, $api_name, $connect, $header, $access ) {
		foreach ( $request->getHeaders() as $name => $value ) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$body = array();
		foreach( $request->getParsedBody() as $name => $value ) $body[$name] = $value;

		$params = [];
		foreach( $request->getQueryParams() as $name => $value ) $params[$name] = $value;

		$api_key = $params['x-api-key'] ?? $header['x_api_key'];

		if (empty($body))
			return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация пуста: Заполните POST-форму и попробуйте снова!'), JSON_UNESCAPED_UNICODE));

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error']), JSON_UNESCAPED_UNICODE));

		$access['full'] = $checkAccess['admin'];
		$access['can_write'] = $checkAccess['write'];

		if ($access['full'] || $access['can_write']) {

			$names = array();
			$values = array();

			foreach ( $body as $name => $value ) {
				$keyNum = array_search($name, array_column($possibleData, 'name'));

				if ($keyNum !== false) {
					$keyData = $possibleData[$keyNum];

					if ( $keyData['post'] === false) continue;

					if ( $keyData['required'] && empty($value))
						return $response->withStatus(400)->getBody()->write(json_encode(array('error' => "Требуемая информация отсутствует: {$name}!"), JSON_UNESCAPED_UNICODE));

					$names[] = $name;
					$values[] = defType(checkLength($value, $keyData['length']), $keyData['type']);

				}
			}

			$names = implode(', ', $names);
			$values = implode(', ', $values);

			$sql = "INSERT INTO " . PREFIX . "_{$api_name} ({$names}) VALUES ({$values})";
			$connect->query( $sql );

			// Почему я не люблю MySQL? Потому что нельзя вернуть данные сразу после добавления в базу данных!
			// All Heil PostgreSQL! `INSERT INTO xxx (yyy) VALUES (zzz) RETURNING *`! Вот так просто!
			// Но нет, в MySQL нужно строить такой костыль!!!
			$lastID = $connect->lastInsertId();
			$sql = "SELECT * FROM " . PREFIX . "_{$api_name} WHERE id = :id";
			$data = $connect->row($sql, array('id' => $lastID));

			$cache = new CacheSystem($api_name, $sql);
			$cache->clear($api_name);
			$cache->setData($data);

			$response->withStatus( 200 )->getBody()->write( $data );

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на добавление новых данных!'), JSON_UNESCAPED_UNICODE));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	$this->put('/{id:[0-9]+}[/]', function (Request $request, Response $response, Array $args) use ($possibleData, $api_name, $connect, $header, $access ) {
		foreach ( $request->getHeaders() as $name => $value ) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$body = array();
		foreach( $request->getParsedBody() as $name => $value ) $body[$name] = $value;

		$params = [];
		foreach( $request->getQueryParams() as $name => $value ) $params[$name] = $value;

		$api_key = $params['x-api-key'] ?? $header['x_api_key'];
		if (empty($body))
			return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация пуста: Заполните POST-форму и попробуйте снова!'), JSON_UNESCAPED_UNICODE));

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error']), JSON_UNESCAPED_UNICODE));

		$access['full'] = $checkAccess['admin'];
		$access['can_write'] = $checkAccess['write'];

		if ($access['full'] || $access['can_write']) {

			$id = $args['id'];
			if (!(int)$id)
				return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация отсутствует: ID!'), JSON_UNESCAPED_UNICODE));
			$values = array();

			foreach ( $body as $name => $value ) {
				if ( defType($value) !== null && in_array($name, $possibleData)) {
					$keyNum = array_search($name, array_column($possibleData, 'name'));

					if ($keyNum !== false) {
						$keyData = $possibleData[$keyNum];

						$values[] ="{$name} = " . defType(checkLength($value, $keyData['length']), $keyData['type']);

					}
				}
			}
			$values = implode(', ', $values);

			$sql = 'UPDATE '. PREFIX . "_{$api_name} SET {$values} WHERE id = :id";
			$connect->query( $sql, array('id' => $id) );

			$sql = 'SELECT * FROM '. PREFIX . "_{$api_name} WHERE id = :id";
			$data = $connect->row($sql, array('id' => $id));

			$cache = new CacheSystem($api_name, $sql);
			$cache->clear($api_name);
			$cache->setData($data);

			$response->withStatus( 200 )->getBody()->write( $data );

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на изменение данных!'), JSON_UNESCAPED_UNICODE));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});
	$this->delete('/{id:[0-9]+}[/]', function (Request $request, Response $response, Array $args) use ($possibleData, $api_name, $connect, $header, $access ) {
		foreach ( $request->getHeaders() as $name => $value ) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$params = [];
		foreach( $request->getQueryParams() as $name => $value ) $params[$name] = $value;

		$api_key = $params['x-api-key'] ?? $header['x_api_key'];

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error']), JSON_UNESCAPED_UNICODE));

		$access['full'] = $checkAccess['admin'];
		$access['can_delete'] = $checkAccess['delete'];

		if ($access['full'] || $access['can_delete']) {

			$id = $args['id'];
			if (!(int)$id)
				return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация отсутствует: ID!'), JSON_UNESCAPED_UNICODE));

			$sql = 'DELETE FROM '. PREFIX . "_{$api_name} WHERE id = {$id}";
			$connect->query( $sql );

			$cache = new CacheSystem($api_name, $sql);
			$cache->clear($api_name);

			$response->withStatus( 200 )->getBody()->write( json_encode( array('success' => 'Данные успешно удалены!'), JSON_UNESCAPED_UNICODE) );

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на удаление данных!'), JSON_UNESCAPED_UNICODE));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	// Own routing Add
});
