<?php

if (!defined('DATALIFEENGINE')) {
	header('HTTP/1.1 403 Forbidden');
	header('Location: ../../');

	exit('Hacking attempt!');
}

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$api_name = 'admin_sections';
$possibleData = [
	[
		'name' => 'id',
		'type' => 'integer',
		'required' => false,
		'post' => false,
		'length' => 0
	],
	[
		'name' => 'name',
		'type' => 'string',
		'required' => false,
		'post' => true,
		'length' => 100
	],
	[
		'name' => 'title',
		'type' => 'string',
		'required' => false,
		'post' => true,
		'length' => 255
	],
	[
		'name' => 'descr',
		'type' => 'string',
		'required' => false,
		'post' => true,
		'length' => 255
	],
	[
		'name' => 'icon',
		'type' => 'string',
		'required' => false,
		'post' => true,
		'length' => 255
	],
	[
		'name' => 'allow_groups',
		'type' => 'string',
		'required' => false,
		'post' => true,
		'length' => 255
	],
];

// possibleData
// $possibleData[] = array(
//                  'name' => 'DBColumn name',
//                  'type' => "Type of value",
//                  'required' => true/false,   // Обязательное поле?
//                  'post' => true/false,       // Разрешить использовать при добавлении или редактуре?
//                  'length' => 0,				// Указывается ограничение для типа string. Содержимое будет обрезаться при нарушении макс. значения
// );
// possibleData Add );

$app->group('/'.$api_name, function () use ($connect, $api_name, $possibleData) {
	$header = [];
	$access = [
		'full' => false,
		'can_read' => false,
		'can_write' => false,
		'can_delete' => false,
	];

	$this->get('[/]', function (Request $request, Response $response, array $args) use ($possibleData, $api_name, $connect, $header, $access) {
		foreach ($request->getHeaders() as $name => $value) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$checkAccess = checkAPI($api_key, $api_name);

		if (isset($checkAccess['error'])) {
			return $response->withStatus(400)->getBody()->write(json_encode(['error' => $checkAccess['error']]));
		}

		$access['full'] = $checkAccess['admin'];
		$access['can_read'] = $checkAccess['read'];
		$access['own_only'] = $checkAccess['own'];

		if ($access['full'] || $access['can_read']) {
			$orderBy = $header['orderby'] ?: 'id';
			$sort = $header['sort'] ?: 'DESC';
			$limit = $header['limit'] ? 'LIMIT ' . (int)$header['limit'] : '';

			$possibleParams = '';

			foreach ($header as $data => $value) {
				$keyData = array_search($data, array_column($possibleData, 'name'));
				if (false !== $keyData) {
					$postData = $possibleData[$keyData];
					if (0 === strlen($possibleParams)) {
						$possibleParams .= " WHERE {$data}".getComparer($header[$data], $postData['type']);
					} else {
						$possibleParams .= " AND {$data}".getComparer($header[$data], $postData['type']);
					}
				}
			}

			$sql = 'SELECT * FROM '.PREFIX."_{$api_name} {$possibleParams} ORDER by {$orderBy} {$sort} {$limit}";

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
			$response->withStatus(400)->getBody()->write(json_encode(['error' => 'У вас нет прав на просмотр данных!']));
		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	$this->post('[/]', function (Request $request, Response $response, array $args) use ($possibleData, $api_name, $connect, $header, $access) {
		foreach ($request->getHeaders() as $name => $value) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$body = [];
		foreach ($request->getParsedBody() as $name => $value) {
			$body[$name] = $value;
		}

		if (empty($body)) {
			return $response->withStatus(400)->getBody()->write(json_encode(['error' => 'Требуемая информация пуста: Заполните POST-форму и попробуйте снова!']));
		}
		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) {
			return $response->withStatus(400)->getBody()->write(json_encode(['error' => $checkAccess['error']]));
		}
		$access['full'] = $checkAccess['admin'];
		$access['can_write'] = $checkAccess['write'];

		if ($access['full'] || $access['can_write']) {
			$names = [];
			$values = [];

			foreach ($body as $name => $value) {
				$keyNum = array_search($name, array_column($possibleData, 'name'));

				if (false !== $keyNum) {
					$keyData = $possibleData[$keyNum];

					if (false === $keyData['post']) {
						continue;
					}
					if ($keyData['required'] && empty($value)) {
						return $response->withStatus(400)->getBody()->write(json_encode(['error' => "Требуемая информация отсутствует: {$name}!"]));
					}
					$names[] = $name;
					$values[] = defType(checkLength($value, $keyData['length']), $keyData['type']);
				}
			}

			$names = implode(', ', $names);
			$values = implode(', ', $values);

			try {
				$sql = 'INSERT INTO '.PREFIX."_{$api_name} ({$names}) VALUES ({$values})";
				$connect->query($sql);
			} catch (Exception $e) {
				return $response->withStatus(500)->getBody()->write(json_encode(['error' => "{$e->getMessage()}!"]));
			}

			// Почему я не люблю MySQL? Потому что нельзя вернуть данные сразу после добавления в базу данных!
			// All Heil PostgreSQL! `INSERT INTO xxx (yyy) VALUES (zzz) RETURNING *`! Вот так просто!
			// Но нет, в MySQL нужно строить такой костыль!!!
			$lastID = $connect->lastInsertId();

			try {
				$sql = 'SELECT * FROM '.PREFIX."_{$api_name} WHERE id = :id";
				$data = $connect->row($sql, ['id' => $lastID]);

				$cache = new CacheSystem($api_name, $sql);
				$cache->clear($api_name);
				$cache->setData($data);
			} catch (Exception $e) {
				return $response->withStatus(500)->getBody()->write(json_encode(['error' => "{$e->getMessage()}!"]));
			}

			$response->withStatus(200)->getBody()->write(json_encode($data));
		} else {
			$response->withStatus(400)->getBody()->write(json_encode(['error' => 'У вас нет прав на добавление новых данных!']));
		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	$this->put('/{id:[0-9]+}[/]', function (Request $request, Response $response, array $args) use ($possibleData, $api_name, $connect, $header, $access) {
		foreach ($request->getHeaders() as $name => $value) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$body = [];
		foreach ($request->getParsedBody() as $name => $value) {
			$body[$name] = $value;
		}

		if (empty($body)) {
			return $response->withStatus(400)->getBody()->write(json_encode(['error' => 'Требуемая информация пуста: Заполните POST-форму и попробуйте снова!']));
		}
		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) {
			return $response->withStatus(400)->getBody()->write(json_encode(['error' => $checkAccess['error']]));
		}
		$access['full'] = $checkAccess['admin'];
		$access['can_write'] = $checkAccess['write'];

		if ($access['full'] || $access['can_write']) {
			$id = $args['id'];
			if (!intval($id)) {
				return $response->withStatus(400)->getBody()->write(json_encode(['error' => 'Требуемая информация отсутствует: ID!']));
			}
			$values = [];

			foreach ($body as $name => $value) {
				if (null !== defType($value) && in_array($name, $possibleData)) {
					$keyNum = array_search($name, array_column($possibleData, 'name'));

					if ($keyNum !== false) {
						$keyData = $possibleData[$keyNum];

						$values[] ="{$name} = " . defType(checkLength($value, $keyData['length']), $keyData['type']);

					}
				}
			}
			$values = implode(', ', $values);

			$sql = 'UPDATE '.PREFIX."_{$api_name} SET {$values} WHERE id = :id";
			$connect->query($sql, ['id' => $id]);

			$sql = 'SELECT * FROM '.PREFIX."_{$api_name} WHERE id = :id";
			$data = $connect->row($sql, ['id' => $id]);

			$cache = new CacheSystem($api_name, $sql);
			$cache->clear($api_name);
			$cache->setData($data);

			$response->withStatus(200)->getBody()->write(json_encode($data));
		} else {
			$response->withStatus(400)->getBody()->write(json_encode(['error' => 'У вас нет прав на изменение данных!']));
		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});
	$this->delete('/{id:[0-9]+}[/]', function (Request $request, Response $response, array $args) use ($api_name, $connect, $header, $access) {
		foreach ($request->getHeaders() as $name => $value) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) {
			return $response->withStatus(400)->getBody()->write(json_encode(['error' => $checkAccess['error']]));
		}
		$access['full'] = $checkAccess['admin'];
		$access['can_delete'] = $checkAccess['delete'];

		if ($access['full'] || $access['can_delete']) {
			$id = $args['id'];
			if (!intval($id)) {
				return $response->withStatus(400)->getBody()->write(json_encode(['error' => "Требуемая информация отсутствует: {$id}!"]));
			}
			$sql = 'DELETE FROM '.PREFIX."_{$api_name} WHERE id = {$id}";
			$connect->query($sql);

			$cache = new CacheSystem($api_name, $sql);
			$cache->clear($api_name);

			$response->withStatus(200)->getBody()->write(json_encode(['success' => 'Данные успешно удалены!']));
		} else {
			$response->withStatus(400)->getBody()->write(json_encode(['error' => 'У вас нет прав на удаление данных!']));
		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	// Own routing Add
});
