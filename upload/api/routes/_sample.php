<?php
if( !defined( 'DATALIFEENGINE' ) ) {
    header( "HTTP/1.1 403 Forbidden" );
    header ( 'Location: ../../' );
    die( "Hacking attempt!" );
}

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// указывается название таблицы
$api_name = 'xfsearch';
// Массив с данными таблицы
$possibleData = array(
	array(
		'name' => 'DBColumn name',  // Название поля
		'type' => "Type of value",  // integer, string, boolean, double
		'required' => true/false,   // Обязательное поле?
		'post' => true/false,       // Разрешить использовать при добавлении или редактуре?
		'length' => 0,       		// Указывается ограничение для типа string. Содержимое будет обрезаться при нарушении макс. значения
	),
);

// Даём возможность добавить дополнительные поля следующим шаблоном:
// $possibleData[] = array(
//                  'name' => 'DBColumn name',
//                  'type' => "Type of value",  // integer, string, boolean, double
//                  'required' => true/false,   // Обязательное поле?
//                  'post' => true/false,       // Разрешить использовать при добавлении или редактуре?
//                  'length' => 0,       // Указывается ограничение для типа string. Содержимое будет обрезаться при нарушении макс. значения
// );
// Оставляем строчку как ориентир
// possibleData Add

$app->group('/' . $api_name, function ( ) use ( $connect, $api_name, $possibleData ) {
	$header = array();

	// Сразу даём понять, какие уровни доступа есть
	$access = array(
		'full' => false,
		'can_read' => false,
		'can_write' => false,
		'can_delete' => false,
		'own_only' => false,
	);

	// Метод GET
	$this->get('[/]', function (Request $request, Response $response, Array $args) use ($possibleData, $api_name, $connect, $header, $access ) {
		// Получаем все данные из HEADER
		foreach ( $request->getHeaders() as $name => $value ) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		// Проверяем ключ API в базе данных и доступ к нему
		$checkAccess = checkAPI($header['x_api_key'], $api_name);
		// Если есть ошибка, то возвращает её
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error'])));

		// Перенемаем значения от API ключа
		$access['full'] = $checkAccess['admin'];
		$access['can_read'] = $checkAccess['read'];
		$access['own_only'] = $checkAccess['own'];

		// Если доступ полный или есть разрешение на чтение
		if ($access['full'] || $access['can_read']) {

			// Стандртные методы фильтрации
			$orderBy = $header['orderby'] ?: 'id';                              // Сортировка по значению
			$sort = $header['sort'] ?: 'DESC';                                  // Сортировка по убыванию или возрастанию (ASC, DESC)
			$limit = $header['limit'] ? 'LIMIT '.(int)$header['limit'] : '';    // Ограниченный вывод

			// Определяем будущий параметр поиска по базе данных
			$possibleParams = '';

			foreach ( $header as $data => $value) {
				// Находим позицию ключа
				$keyData = array_search($data, array_column($possibleData, 'name'));

				// Если нет полного доступа, то проверяем входящие данные
				if (!$access['full'])
					// Проверяем ячейку на параметр пользователя (ID или имя)
					// Если ячейка соответствует значению в массиве, то пропускаем этот круг проверки
					if (in_array($data, ['name', 'user_id', 'autor', 'author', 'member', 'user'])
						&& $access['own_only']['access']) continue;

				// Если ключ и значение есть в массиве таблицы, то начинаем пополнять параметр поиска
				if ($keyData !== false) {
					// получаем данные о поле таблицы
					$postData = $possibleData[$keyData];
					// Если параметр поиска пуст, то добавляет значение 'WHERE'
					// В противном случае - просто добавляем
					// getComparer проверяет тип данных и символ сравнения
					if ( strlen( $possibleParams ) === 0 ) $possibleParams .= " WHERE {$data}" . getComparer( $header[$data], $postData['type'] );
					else $possibleParams .= " AND {$data}" . getComparer( $header[$data], $postData['type'] );
				}
			}
			// Если нет полного доступа, то проверяем входящие данные
			if (!$access['full']) {
				// Если переменная с возможной фильтрацией пуста и настроен доступ лишь на вывод данных пользователя
				// с запрошенным API ключём, то проставляем фильтрацию
				if (strlen($possibleParams) === 0 && $access['own_only']['access'])
					$possibleParams .= " WHERE user_id = {$access['own_only']['user_id']} OR name = '{$access['own_only']['user_name']}'";
				else $possibleParams .= " AND (user_id = {$access['own_only']['user_id']} OR name = '{$access['own_only']['user_name']}')";
			}

			// Оформляем SQL запрос
			$sql = 'SELECT * FROM '. PREFIX . "_{$api_name} {$possibleParams} ORDER by {$orderBy} {$sort} {$limit}";

			// Включаем кеш
			// $cache = new CacheSystem('название таблицы', 'идентификационы набор символов (SQL запрос)', 'сохраняемые данные', 'тип кеша', 'путь до папки с кешем');
			$getData = new CacheSystem($api_name, $sql);

			// Если кеш пустой или отсутствует
			if (empty($getData->get())) {
				// то мы берём данные из базы данных
				$data = $connect->query($sql);
				// передаём данные в кеш
				$getData->setData(json_encode($data));
				// сохраняем
				$data = $getData->create();
			} else {
				// или просто передаём данные из кеша
				$data = json_decode($getData->get(), true);
			}

			// оформляем ответ
			$response->withStatus( 200 )->getBody()->write( json_encode( $data ) );

		} else {

			// оформляем отказ в доступе
			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на просмотр данных!')));

		}

		// возвращаем ответ
		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	// Метод POST
	$this->post('[/]', function (Request $request, Response $response, Array $args) use ($possibleData, $api_name, $connect, $header, $access ) {
		foreach ( $request->getHeaders() as $name => $value ) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		// Начинаем формировать данные
		$body = array();
		// Заполняем массив с данными
		foreach( $request->getParsedBody() as $name => $value ) $body[$name] = $value;

		// Если массив данных пуст, то возвращаем ошибку
		if (empty($body))
			return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация пуста: Заполните POST-форму и попробуйте снова!')));

		$checkAccess = checkAPI($header['x_api_key'], $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error'])));

		$access['full'] = $checkAccess['admin'];
		$access['can_write'] = $checkAccess['write'];

		if ($access['full'] || $access['can_write']) {

			// Формируем массивы с ключами и значениями
			$names = array();
			$values = array();

			foreach ( $body as $name => $value ) {
				$keyNum = array_search($name, array_column($possibleData, 'name'));

				if ($keyNum !== false) {
					$keyData = $possibleData[$keyNum];

					// предотвращаем передачу первичных ключей (primary key)
					if ( $keyData['post'] === false) continue;

					// если обязательное поле не заполнено, то возвращаем ошибку
					if ( $keyData['required'] && empty($value))
						return $response->withStatus(400)->getBody()->write(json_encode(array('error' => "Требуемая информация отсутствует: {$name}!")));

					$names[] = $name;
					// проверяем тип данных
					$values[] = defType(checkLength($value, $keyData['length']), $keyData['type']);

				}
			}

			$names = implode(', ', $names);
			$values = implode(', ', $values);

			// Добавляем в базу данных
			$sql = "INSERT INTO " . PREFIX . "_{$api_name} ({$names}) VALUES ({$values})";
			$connect->query( $sql );

			// Почему я не люблю MySQL? Потому что нельзя вернуть данные сразу после добавления в базу данных!
			// All Heil PostgreSQL! `INSERT INTO xxx (yyy) VALUES (zzz) RETURNING *`! Вот так просто!
			// Но нет, в MySQL нужно строить такой костыль!!!
			$lastID = $connect->lastInsertId();
			$sql = "SELECT * FROM " . PREFIX . "_{$api_name} WHERE id = :id";
			$data = $connect->row($sql, array('id' => $lastID));

			$cache = new CacheSystem($api_name, $sql);
			// очищаем кеш, чтобы сформировать новый с новыми значениями
			$cache->clear($api_name);
			$cache->setData(json_encode($data));
			$cache->create();

			$response->withStatus( 200 )->getBody()->write( json_encode( $data ) );

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на добавление новых данных!')));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	// Метод PUT, для обновления данных
	$this->put('/{id:[0-9]+}[/]', function (Request $request, Response $response, Array $args) use ($possibleData, $api_name, $connect, $header, $access ) {
		foreach ( $request->getHeaders() as $name => $value ) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$body = array();
		foreach( $request->getParsedBody() as $name => $value ) $body[$name] = $value;

		if (empty($body))
			return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация пуста: Заполните POST-форму и попробуйте снова!')));

		$checkAccess = checkAPI($header['x_api_key'], $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error'])));

		$access['full'] = $checkAccess['admin'];
		$access['can_write'] = $checkAccess['write'];

		if ($access['full'] || $access['can_write']) {

			$id = $args['id'];
			if (!(int)$id)
				return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация отсутствует: ID!')));
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
			$cache->setData(json_encode($data));

			$response->withStatus( 200 )->getBody()->write( json_encode( $data ) );

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на изменение данных!')));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	// Метод DELETE
	$this->delete('/{id:[0-9]+}[/]', function (Request $request, Response $response, Array $args) use ($possibleData, $api_name, $connect, $header, $access ) {
		foreach ( $request->getHeaders() as $name => $value ) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$checkAccess = checkAPI($header['x_api_key'], $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error'])));

		$access['full'] = $checkAccess['admin'];
		$access['can_delete'] = $checkAccess['delete'];

		if ($access['full'] || $access['can_delete']) {

			$id = $args['id'];
			if (!(int)$id)
				return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация отсутствует: ID!')));

			$sql = 'DELETE FROM '. PREFIX . "_{$api_name} WHERE id = {$id}";
			$connect->query( $sql );

			$cache = new CacheSystem($api_name, $sql);
			$cache->clear($api_name);

			$response->withStatus( 200 )->getBody()->write( json_encode( array('success' => 'Данные успешно удалены!') ) );

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на удаление данных!')));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});


	// Отметка для добавления своих методов и функций
	// Own routing Add
});
