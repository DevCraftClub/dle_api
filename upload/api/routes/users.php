<?php
global $app, $connect, $dle_api;
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

require_once DLEPlugins::Check(ENGINE_DIR . '/api/api.class.php');

$api_name     = 'users';
$possibleData = [
	[
		'name'     => 'email',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 50
	],
	[
		'name'     => 'password',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	],
	[
		'name'     => 'name',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 40
	],
	[
		'name'     => 'user_id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 11
	],
	[
		'name'     => 'news_num',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	],
	[
		'name'     => 'comm_num',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	],
	[
		'name'     => 'user_group',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	],
	[
		'name'     => 'lastdate',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 20
	],
	[
		'name'     => 'reg_date',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 20
	],
	[
		'name'     => 'banned',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 5
	],
	[
		'name'     => 'allow_mail',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'info',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	],
	[
		'name'     => 'signature',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	],
	[
		'name'     => 'foto',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	],
	[
		'name'     => 'fullname',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 100
	],
	[
		'name'     => 'land',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 100
	],
	[
		'name'     => 'favorites',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	],
	[
		'name'     => 'pm_all',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	],
	[
		'name'     => 'pm_unread',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	],
	[
		'name'     => 'time_limit',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 20
	],
	[
		'name'     => 'xfields',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	],
	[
		'name'     => 'allowed_ip',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	],
	[
		'name'     => 'hash',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 32
	],
	[
		'name'     => 'logged_ip',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 46
	],
	[
		'name'     => 'restricted',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'restricted_days',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 4
	],
	[
		'name'     => 'restricted_date',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 15
	],
	[
		'name'     => 'timezone',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 100
	],
	[
		'name'     => 'news_subscribe',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'comments_reply_subscribe',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'twofactor_auth',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	],
	[
		'name'     => 'cat_add',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 500
	],
	[
		'name'     => 'cat_allow_addnews',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 500
	],
	[
		'name'     => 'twofactor_secret',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 16
	],
];

$Cruds = new CrudController($api_name, $possibleData, ['name', 'user_id'], orderBy: 'user_id', prefix: 'user', pk: 'user_id');

$app->group(
	"/{$api_name}",
	function (RouteCollectorProxy $sub) use ($Cruds, $api_name, $connect, $dle_api) {
		$sub->get('[/]', [$Cruds, 'handleGet']);
		$sub->get('/{id}[/]', [$Cruds, 'handleGetSingle']);
		$sub->put('/{id}[/]', [$Cruds, 'handlePut']);
		$sub->delete('/{id}[/]', [$Cruds, 'handleDelete']);
		$sub->post(
			'/register[/]',
			function (ServerRequestInterface $request, ResponseInterface $response, array $args) use ($Cruds, $api_name, $connect, $dle_api) {
				global $USERprefix, $possibleData;

				$header      = $Cruds->parseHeader($request);
				$params      = $request->getQueryParams() ?: [];
				$api_key     = $Cruds->extractApiKey($params, $header);
				$checkAccess = checkAPI($api_key, $api_name);
				$body        = filter_var_array($request->getParsedBody());
				$name        = filter_var($body['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$password    = filter_var($body['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$email       = filter_var($body['email'], FILTER_VALIDATE_EMAIL);
				$user_group  = filter_var($body['user_group'], FILTER_VALIDATE_INT);

				if (isset($checkAccess['error'])) {
					$response->getBody()->write(
						json_encode(['error' => $checkAccess['error']],
									JSON_UNESCAPED_UNICODE)
					);
					return $response->withHeader(
						'Content-Type',
						'application/json; charset=UTF-8'
					)->withStatus(405);
				}

				if (!$name || !$password || !$email) {
					$response->getBody()->write(
						json_encode(['error' => 'Имя пользователя, почта и пароль не должны быть пустыми!'],
									JSON_UNESCAPED_UNICODE)
					);
					return $response->withHeader(
						'Content-Type',
						'application/json; charset=UTF-8'
					)->withStatus(400);
				}

				$this->access['full']      = $checkAccess['admin'];
				$this->access['can_write'] = $checkAccess['write'];

				if ($this->access['full'] || $this->access['can_write']) {
					$user_group = $user_group ?: 4;

					$user_register = $dle_api->external_register($name, $password, $email, $user_group);
					if ($user_register === 1) {
						$sql_values = [];

						foreach ($body as $name => $value) {
							if (in_array($name, ['name', 'email', 'password', 'user_group'])) continue;

							$keyNum = array_search($name, array_column($possibleData, 'name'));

							if ($keyNum !== false) {
								$keyData = $possibleData[$keyNum];

								$sql_values[] = "{$name} = " . defType(
										checkLength($value, $keyData['length']),
										$keyData['type']
									);

							}
						}

						$values = implode(', ', $sql_values);

						$sql = "UPDATE {$USERprefix}_{$api_name} SET {$values} WHERE name = :name";
						$connect::update($sql, ['name' => $body['name']]);
						$lastID = $connect::getPdo()->lastInsertId();
						$sql    = "SELECT * FROM {$USERprefix}_{$api_name} WHERE user_id = :id";
						$data   = $connect::selectOne($sql, ['id' => $lastID]);

						$cache = new CacheSystem($api_name, $sql);
						$cache->clear($api_name);
						$cache->setData($data);

						$response->withStatus(200)->getBody()->write(json_encode($data));
					} else if ($user_register === -1) {
						$response->withStatus(400)->getBody()->write(
							json_encode(['error' => 'Пользователь с таким именем уже существует!'])
						);
					} else if ($user_register === -2) {
						$response->withStatus(400)->getBody()->write(
							json_encode(['error' => 'Пользователь с такой электронной почтой уже существует!'])
						);
					} else if ($user_register === -3) {
						$response->withStatus(400)->getBody()->write(
							json_encode(['error' => 'Введённая почта не действительна или задана не корректно!'])
						);
					} else if ($user_register === -4) {
						$response->withStatus(400)->getBody()->write(
							json_encode(['error' => 'Заданной группы не существует!'])
						);
					} else {
						$response->withStatus(400)->getBody()->write(
							json_encode(['error' => 'Регистрация пользователя увенчалась провалом!'])
						);
					}
				} else {
					$response->withStatus(405)->getBody()->write(
						json_encode(['error' => "У вас нет прав на добавление новых данных!"])
					);
				}

				return $response->withHeader(
					'Content-Type',
					'application/json; charset=UTF-8'
				);
			}
		);
		$sub->post('/auth[/]', function (ServerRequestInterface $request, ResponseInterface $response, array $args) use ($Cruds, $api_name, $connect, $dle_api) {
				global $USERprefix;

				$header      = $Cruds->parseHeader($request);
				$params      = $request->getQueryParams() ?: [];
				$api_key     = $Cruds->extractApiKey($params, $header);
				$checkAccess = checkAPI($api_key, $api_name);
				[$name, $password] = filter_var_array($request->getParsedBody(), [
					'name'     => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
					'password' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
				]);

				if (isset($checkAccess['error'])) {
					$response->getBody()->write(
						json_encode(['error' => $checkAccess['error']],
									JSON_UNESCAPED_UNICODE)
					);
					return $response->withHeader(
						'Content-Type',
						'application/json; charset=UTF-8'
					)->withStatus(405);
				}

				if (!$name || !$password) {
					$response->getBody()->write(
						json_encode(['error' => 'Имя пользователя и пароль не должны быть пустыми!'],
									JSON_UNESCAPED_UNICODE)
					);
					return $response->withHeader(
						'Content-Type',
						'application/json; charset=UTF-8'
					)->withStatus(400);
				}

				$this->access['full']      = $checkAccess['admin'];
				$this->access['can_write'] = $checkAccess['write'];

				if ($this->access['full'] || $this->access['can_write']) {
					if ($dle_api->external_auth($name, $password)) {

						$sql = "SELECT * FROM {$USERprefix}_{$api_name} WHERE name = :name";

						$getData = new CacheSystem($api_name, $sql);
						if (check_response($getData->get())) {
							$data = $connect::selectOne($sql, ['name' => $name]);
							$getData->setData($data);
							$data = $getData->create();
						} else {
							$data = $getData->get();
						}

						$response->withStatus(200)->getBody()->write(json_encode($data));
					} else {
						$response->withStatus(400)->getBody()->write(
							json_encode(['error' => 'Авторизация пользователя увенчалась провалом!'])
						);
					}
				} else {
					$response->withStatus(405)->getBody()->write(
						json_encode(['error' => "У вас нет прав на добавление новых данных!"])
					);
				}

				return $response->withHeader(
					'Content-Type',
					'application/json; charset=UTF-8'
				);
			}
		);

		// Own routing Add
	}
);
