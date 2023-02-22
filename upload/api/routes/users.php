<?php
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

include_once(ENGINE_DIR . '/api/api.class.php');

$dle_api = new DLE_API();

$api_name     = 'users';
$possibleData = array(
	array(
		'name'     => 'email',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 50
	),
	array(
		'name'     => 'password',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	),
	array(
		'name'     => 'name',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 40
	),
	array(
		'name'     => 'user_id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 11
	),
	array(
		'name'     => 'news_num',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	),
	array(
		'name'     => 'comm_num',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	),
	array(
		'name'     => 'user_group',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'lastdate',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 20
	),
	array(
		'name'     => 'reg_date',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 20
	),
	array(
		'name'     => 'banned',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 5
	),
	array(
		'name'     => 'allow_mail',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'info',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'signature',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'foto',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	),
	array(
		'name'     => 'fullname',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 100
	),
	array(
		'name'     => 'land',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 100
	),
	array(
		'name'     => 'favorites',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'pm_all',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'pm_unread',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'time_limit',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 20
	),
	array(
		'name'     => 'xfields',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'allowed_ip',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	),
	array(
		'name'     => 'hash',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 32
	),
	array(
		'name'     => 'logged_ip',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 46
	),
	array(
		'name'     => 'restricted',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'restricted_days',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 4
	),
	array(
		'name'     => 'restricted_date',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 15
	),
	array(
		'name'     => 'timezone',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 100
	),
	array(
		'name'     => 'news_subscribe',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'comments_reply_subscribe',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'twofactor_auth',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'cat_add',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 500
	),
	array(
		'name'     => 'cat_allow_addnews',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 500
	),
);

// possibleData
// $possibleData[] = array(
//                  'name' => 'DBColumn name',
//                  'type' => "Type of value",  // integer, string, boolean, double
//                  'required' => true/false,   // Обязательное поле?
//                  'post' => true/false,       // Разрешить использовать при добавлении или редактуре?
//                  'length' => 0,				// Указывается ограничение для типа string. Содержимое будет обрезаться при нарушении макс. значения
// );
// possibleData Add

$app->group('/' . $api_name, function () use ($connect, $api_name, $possibleData) {
	$header = array();
	$access = array(
		'full'       => false,
		'can_read'   => false,
		'can_write'  => false,
		'can_delete' => false,
	);

	$this->get('[/]', function (Request $request, Response $response, array $args) use ($possibleData, $api_name, $connect, $header, $access) {
		foreach ($request->getHeaders() as $name => $value) {
			$name          = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$params = [];
		foreach ($request->getQueryParams() as $name => $value) $params[$name] = $value;

		$api_key  = $params['x-api-key'] ?? $header['x_api_key'];
		$order_by = $params['orderby'] ?? $header['orderby'];
		$sort     = $params['sort'] ?? $header['sort'];
		$limit    = $params['limit'] ?? $header['limit'];

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error']), JSON_UNESCAPED_UNICODE));

		$access['full']     = $checkAccess['admin'];
		$access['can_read'] = $checkAccess['read'];
		$access['own_only'] = $checkAccess['own'];

		if ($access['full'] || $access['can_read']) {
			$orderBy = $header['orderby'] ?: 'user_id';
			$sort    = $header['sort'] ?: 'DESC';
			$limit   = $header['limit'] ? 'LIMIT ' . (int) $header['limit'] : '';

			$possibleParams = '';

			foreach ($header as $data => $value) {
				$keyData = array_search($data, array_column($possibleData, 'name'));
				if (in_array($data, ['user_id', 'name']) && (strlen($possibleParams) === 0 && (!$access['full'] &&
							$access['own_only']['access']))) continue;
				if ($keyData !== false) {
					$postData = $possibleData[$keyData];
					if (strlen($possibleParams) === 0) $possibleParams .= " WHERE {$data}" . getComparer($header[$data], $postData['type']);
					else $possibleParams .= " AND {$data}" . getComparer($header[$data], $postData['type']);
				}
			}

			if (!$access['full']) {
				if (strlen($possibleParams) === 0 && $access['own_only']['access'])
					$possibleParams .= " WHERE user_id = {$access['own_only']['user_id']} OR name = '{$access['own_only']['user_name']}'";
				else $possibleParams .= " AND (user_id = {$access['own_only']['user_id']} OR name = '{$access['own_only']['user_name']}')";
			}

			$sql = 'SELECT * FROM ' . USERPREFIX . "_{$api_name} {$possibleParams} ORDER by {$orderBy} {$sort} {$limit}";

			$getData = new CacheSystem($api_name, $sql);
			if (empty($getData->get())) {
				$data = $connect->query($sql);
				$getData->setData($data);
				$data = $getData->create();
			} else {
				$data = $getData->get();
			}

			$response->withStatus(200)->getBody()->write($data);

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на просмотр данных!'), JSON_UNESCAPED_UNICODE));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	$this->post('/register[/]', function (Request $request, Response $response, array $args) use ($possibleData, $api_name, $connect, $header, $access) {
		foreach ($request->getHeaders() as $name => $value) {
			$name          = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$body = array();
		foreach ($request->getParsedBody() as $name => $value) $body[$name] = $value;

		$params = [];
		foreach ($request->getQueryParams() as $name => $value) $params[$name] = $value;

		$api_key      = $params['x-api-key'] ?? $header['x_api_key'];
		$requiredData = ['name', 'password', 'email', 'user_group'];
		foreach ($requiredData as $data) {
			if (!isset($body[$data]) && empty($body[$data]))
				return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация пуста: Заполните POST-форму и попробуйте снова!'), JSON_UNESCAPED_UNICODE));
		}

		if (empty($body))
			return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация пуста: Заполните POST-форму и попробуйте снова!'), JSON_UNESCAPED_UNICODE));

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error']), JSON_UNESCAPED_UNICODE));

		$access['full']      = $checkAccess['admin'];
		$access['can_write'] = $checkAccess['write'];

		if ($access['full'] || $access['can_write']) {

			$values = array();

			foreach ($body as $name => $value) {
				$keyNum = array_search($name, array_column($possibleData, 'name'));

				if ($keyNum !== false) {
					$keyData = $possibleData[$keyNum];

					$values[] = "{$name} = " . defType(checkLength($value, $keyData['length']), $keyData['type']);

				}
			}

			$user_register = $dle_api->external_register($body['name'], $body['password'], $body['email'], $body['user_group']);
			if (1 === (int) $user_register) {
				$names  = implode(', ', $names);
				$values = implode(', ', $values);

				$sql = 'UPDATE ' . USERPREFIX . "_{$api_name} SET WHERE name = :name and email = :email";
				$connect->row($sql, ['name' => $body['name'], 'email' => $body['email']]);

				// Почему я не люблю MySQL? Потому что нельзя вернуть данные сразу после добавления в базу данных!
				// All Heil PostgreSQL! `INSERT INTO xxx (yyy) VALUES (zzz) RETURNING *`! Вот так просто!
				// Но нет, в MySQL нужно строить такой костыль!!!
				$lastID = $connect->lastInsertId();
				$sql    = 'SELECT * FROM ' . PREFIX . "_{$api_name} WHERE user_id = :id";
				$data   = $connect->row($sql, ['id' => $lastID]);

				$cache = new CacheSystem($api_name, $sql);
				$cache->clear($api_name);
				$cache->setData($data);

				$response->withStatus(200)->getBody()->write(json_encode($data));
			} elseif (-1 === (int) $user_register) {
				$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Пользователь с таким именем уже существует!')));
			} elseif (-2 === (int) $user_register) {
				$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Пользователь с такой электронной почтой уже существует!')));
			} elseif (-3 === (int) $user_register) {
				$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Введённая почта не действительна или задана не корректно!')));
			} elseif (-4 === (int) $user_register) {
				$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Заданной группы не существует!')));
			}
		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на добавление новых данных!'), JSON_UNESCAPED_UNICODE));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	$this->post('/auth[/]', function (Request $request, Response $response, array $args) use ($possibleData, $api_name, $connect, $header, $access) {
		foreach ($request->getHeaders() as $name => $value) {
			$name          = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$body = array();
		foreach ($request->getParsedBody() as $name => $value) $body[$name] = $value;

		$params = [];
		foreach ($request->getQueryParams() as $name => $value) $params[$name] = $value;

		$api_key      = $params['x-api-key'] ?? $header['x_api_key'];
		$requiredData = ['name', 'password'];
		foreach ($requiredData as $data) {
			if (!isset($body[$data]) && empty($body[$data]))
				return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация пуста: Заполните POST-форму и попробуйте снова!'), JSON_UNESCAPED_UNICODE));
		}

		if (empty($body))
			return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация пуста: Заполните POST-форму и попробуйте снова!'), JSON_UNESCAPED_UNICODE));

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error']), JSON_UNESCAPED_UNICODE));

		$access['full']     = $checkAccess['admin'];
		$access['can_read'] = $checkAccess['read'];

		if ($access['full'] || $access['can_read']) {

			$user_auth = $dle_api->external_auth($body['name'], $body['password']);
			if ($user_auth) {
				$sql = 'SELECT * FROM' . USERPREFIX . "_{$api_name} WHERE name = :name";

				$cache = new CacheSystem($api_name, $sql);
				if (empty($cache->get())) {
					$data = $connect->row($sql, ['name' => $body['name']]);
					$cache->setData($data);
					$cache->create();
				} else {
					$data = json_decode($cache->get(), true);
				}

				$response->withStatus(200)->getBody()->write(json_encode($data));
			} else {
				$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'При авторизации были неверно введены данные входа!')));
			}

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на добавление новых данных!'), JSON_UNESCAPED_UNICODE));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	$this->put('/{id:[0-9]+}[/]', function (Request $request, Response $response, array $args) use ($possibleData, $api_name, $connect, $header, $access) {
		foreach ($request->getHeaders() as $name => $value) {
			$name          = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$body = array();
		foreach ($request->getParsedBody() as $name => $value) $body[$name] = $value;

		$params = [];
		foreach ($request->getQueryParams() as $name => $value) $params[$name] = $value;

		$api_key = $params['x-api-key'] ?? $header['x_api_key'];
		if (empty($body))
			return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация пуста: Заполните POST-форму и попробуйте снова!'), JSON_UNESCAPED_UNICODE));

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error']), JSON_UNESCAPED_UNICODE));

		$access['full']      = $checkAccess['admin'];
		$access['can_write'] = $checkAccess['write'];

		if ($access['full'] || $access['can_write']) {

			$id = $args['id'];
			if (!(int) $id)
				return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация отсутствует: ID!'), JSON_UNESCAPED_UNICODE));
			$values = array();

			foreach ($body as $name => $value) {
				if (defType($value) !== null && in_array($name, $possibleData)) {
					$keyNum = array_search($name, array_column($possibleData, 'name'));

					if ($keyNum !== false) {
						$keyData = $possibleData[$keyNum];

						$values[] = "{$name} = " . defType(checkLength($value, $keyData['length']), $keyData['type']);

					}
				}
			}
			$values = implode(', ', $values);

			$sql = 'UPDATE ' . USERPREFIX . "_{$api_name} SET {$values} WHERE id = :id";
			$connect->query($sql, array('id' => $id));

			$sql  = 'SELECT * FROM ' . USERPREFIX . "_{$api_name} WHERE id = :id";
			$data = $connect->row($sql, array('id' => $id));

			$cache = new CacheSystem($api_name, $sql);
			$cache->clear($api_name);
			$cache->setData($data);

			$response->withStatus(200)->getBody()->write($data);

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на изменение данных!'), JSON_UNESCAPED_UNICODE));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});
	$this->delete('/{id:[0-9]+}[/]', function (Request $request, Response $response, array $args) use ($possibleData, $api_name, $connect, $header, $access) {
		foreach ($request->getHeaders() as $name => $value) {
			$name          = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$params = [];
		foreach ($request->getQueryParams() as $name => $value) $params[$name] = $value;

		$api_key = $params['x-api-key'] ?? $header['x_api_key'];

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error']), JSON_UNESCAPED_UNICODE));

		$access['full']       = $checkAccess['admin'];
		$access['can_delete'] = $checkAccess['delete'];

		if ($access['full'] || $access['can_delete']) {

			$id = $args['id'];
			if (!(int) $id)
				return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация отсутствует: ID!'), JSON_UNESCAPED_UNICODE));

			$sql = 'DELETE FROM ' . USERPREFIX . "_{$api_name} WHERE id = {$id}";
			$connect->query($sql);

			$cache = new CacheSystem($api_name, $sql);
			$cache->clear($api_name);

			$response->withStatus(200)->getBody()->write(json_encode(array('success' => 'Данные успешно удалены!'), JSON_UNESCAPED_UNICODE));

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на удаление данных!'), JSON_UNESCAPED_UNICODE));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	// Own routing Add
});
