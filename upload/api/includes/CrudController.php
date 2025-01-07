<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Класс для обработки CRUD-запросов к базе данных.
 *
 * @since 173.0.36
 */
class CrudController {
	/**
	 * @var database Экземпляр подключения к базе данных.
	 */
	private database $db;
	private string $prefix;
	/**
	 * @var string Имя таблицы базы данных.
	 */
	private string $table;
	/**
	 * @var array Список разрешенных полей для операций чтения и записи.
	 */
	private array $allowedFields;
	/**
	 * @var array Список полей, принадлежащих текущему пользователю.
	 */
	private array $ownFields;
	/**
	 * @var string Поле для сортировки данных по умолчанию.
	 */
	private string $orderBy;
	/**
	 * @var string Направление сортировки по умолчанию (ASC/DESC).
	 */
	private string $sort;
	/**
	 * @var array Список прав доступа пользователя.
	 */
	private array $access
		= [
			'full'       => false,
			'can_read'   => false,
			'can_write'  => false,
			'can_delete' => false,
			'own_only'   => false,
		];

	/**
	 * Конструктор класса.
	 *
	 * @param string $table         Имя таблицы базы данных.
	 * @param array  $allowedFields Список разрешенных полей для операций.
	 * @param array  $ownFields     Поля, относящиеся к текущему пользователю (по умолчанию пустой массив).
	 * @param string $orderBy       Поле для сортировки данных по умолчанию (по умолчанию "id").
	 * @param string $sort          Направление сортировки по умолчанию (по умолчанию "DESC").
	 */
	public function __construct(string $table, array $allowedFields, array $ownFields = [], string $orderBy = 'id', string $sort = 'DESC', string $prefix = 'dle') {
		global $connect, $DLEprefix, $USERprefix;

		$this->db            = $connect;
		$this->table         = $table;
		$this->allowedFields = $allowedFields;
		$this->ownFields     = $ownFields;
		$this->orderBy       = $orderBy;
		$this->sort          = $sort;
		$this->prefix		 = $prefix === 'dle' ? $DLEprefix : $USERprefix;
	}

	/**
	 * Обрабатывает GET-запрос, извлекает данные из базы данных с учетом фильтров и прав доступа,
	 * возвращает результат в формате JSON.
	 *
	 * @param ServerRequestInterface $request  Запрос от клиента.
	 * @param ResponseInterface      $response Ответ для клиента.
	 * @param array                  $args     Массив аргументов маршрута.
	 *
	 * @return ResponseInterface Ответ с данными или сообщением об ошибке.
	 *
	 * @throws Error|JsonException Если возникает ошибка при проверке API-ключа или построении SQL-запроса.
	 */
	public function handleGet(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
		$header = $this->parseHeader($request);
		$params = $request->getQueryParams() ?: [];
		[$orderBy, $sort, $limit] = $this->parseParameters($params, $header);
		$api_key = $this->extractApiKey($params, $header);

		$checkAccess = checkAPI($api_key, $this->table);
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

		$this->access['full']     = $checkAccess['admin'];
		$this->access['can_read'] = $checkAccess['read'];
		$this->access['own_only'] = $checkAccess['own'];

		if ($this->access['full'] || $this->access['can_read']) {
			$limit = $limit ? "LIMIT " . (int)$limit : '';

			$possibleParams = '';

			$filters       = $this->buildFilters($header);
			$accessFilters = $this->buildAccessFilters();
			if (count($filters)) $possibleParams = 'WHERE ' . implode(' AND ', $filters);
			if (count($accessFilters)) {
				if (strlen($possibleParams)) {
					$possibleParams .= 'WHERE ' . implode(' OR ', $accessFilters);
				} else {
					$possibleParams .= ' AND (' . implode(' OR ', $accessFilters) . ')';
				}
			}
		} else {
			$response->getBody()->write(
				json_encode(['error' => "У вас нет прав на просмотр данных!"],
							JSON_UNESCAPED_UNICODE)
			);
			return $response->withHeader(
				'Content-Type',
				'application/json; charset=UTF-8'
			)->withStatus(405);
		}

		$sql = "SELECT * FROM {$this->prefix}_{$this->table} {$possibleParams} ORDER BY {$orderBy} {$sort} {$limit}";

		$getData = new CacheSystem($this->table, $sql);
		if (check_response($getData->get())) {
			$data = $this->db->query($sql, []);
			$getData->setData($data);
			$data = $getData->create();
		} else {
			$data = $getData->get();
		}

		$response->getBody()->write($data);
		return $response->withHeader(
			'Content-Type',
			'application/json; charset=UTF-8'
		)->withStatus(201);
	}

	/**
	 * Обрабатывает POST-запрос на добавление данных в базу.
	 *
	 * @param ServerRequestInterface $request  HTTP-запрос, содержащий параметры и тело запроса.
	 * @param ResponseInterface      $response HTTP-ответ, который будет возвращен после обработки.
	 * @param array                  $args     Дополнительные параметры маршрута.
	 *
	 * @return ResponseInterface HTTP-ответ с результатом выполнения (успех или ошибка).
	 *
	 * @throws Error Если произошла ошибка при работе с базой данных.
	 */
	public function handlePost(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

		$header      = $this->parseHeader($request);
		$params      = $request->getQueryParams() ?: [];
		$body        = $request->getParsedBody();
		$api_key     = $this->extractApiKey($params, $header);
		$checkAccess = checkAPI($api_key, $this->table);

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

		if (empty($body)) {
			$response->getBody()->write(
				json_encode(['error' => 'Содержимое POST-запроса не может быть пустым.'],
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
			$names  = [];
			$values = [];

			foreach ($body as $name => $value) {
				$postData = $this->findPossibleData($name);

				if (!$postData) {
					continue;
				}

				if ($postData['post'] === false) continue;

				if ($postData['required'] && empty($value)) {
					$response->getBody()->write(
						json_encode(
							['error' => "Требуемая информация отсутствует: {$name}!"],
							JSON_UNESCAPED_UNICODE
						)
					);
					return $response->withHeader(
						'Content-Type',
						'application/json; charset=UTF-8'
					)->withStatus(400);
				}
				$names[]  = $name;
				$values[] = defType(
					checkLength(
						$value,
						$postData['length']
					),
					$postData['type']
				);
			}

			$names  = implode(', ', $names);
			$values = implode(', ', $values);

			$sql = "INSERT INTO {$this->prefix}_{$this->table} ({$names}) VALUES ({$values})";
			$this->db->query($sql, []);

			// Почему я не люблю MySQL? Потому что нельзя вернуть данные сразу после добавления в базу данных!
			// All Heil PostgreSQL! `INSERT INTO xxx (yyy) VALUES (zzz) RETURNING *`! Вот так просто!
			// Но нет, в MySQL нужно строить такой костыль!!!
			$lastID = $this->db->lastInsertId();
			$sql    = "SELECT * FROM {$this->prefix}_{$this->table} WHERE id = :id";
			$data   = $this->db->row($sql, ['id' => $lastID]);

			$cache = new CacheSystem($this->table, $sql);
			$cache->clear($this->table);
			$cache->setData($data);

			$response->getBody()->write($data);
		} else {
			$response->getBody()->write(
				json_encode(['error' => "У вас нет прав на добавление новых данных!"])
			);
			return $response->withHeader(
				'Content-Type',
				'application/json; charset=UTF-8'
			)->withStatus(405);
		}

		return $response->withHeader(
			'Content-Type',
			'application/json; charset=UTF-8'
		)->withStatus(201);
	}

	/**
	 * Обрабатывает PUT-запрос для изменения данных в базе.
	 *
	 * @param ServerRequestInterface $request  Объект запроса с информацией о запросе клиента.
	 * @param ResponseInterface      $response Объект ответа, который будет возвращен клиенту.
	 * @param array                  $args     Аргументы маршрута, в частности ID записи для обновления.
	 *
	 * @return ResponseInterface Ответ с результатом обработки запроса.
	 *
	 * @throws Error В случае возникновения ошибок на уровне базы данных или другой логики.
	 *
	 * Обработка включает следующие этапы:
	 * 1. Проверка заголовков и параметров запроса на наличие API-ключа.
	 * 2. Проверка прав доступа на изменение данных.
	 * 3. Проверка наличия тела запроса.
	 * 4. Обновление записи в базе данных на основе переданных данных.
	 * 5. Очистка кэша и возврат обновленных данных.
	 *
	 * Ответы:
	 * - Код 405: Если API-ключ недействителен или недостаточно прав.
	 * - Код 400: Если тело запроса пустое или ID записи некорректен.
	 * - Код 201: При успешном обновлении записи.
	 */
	public function handlePut(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
		$header      = $this->parseHeader($request);
		$params      = $request->getQueryParams() ?: [];
		$body        = $request->getParsedBody();
		$api_key     = $this->extractApiKey($params, $header);
		$checkAccess = checkAPI($api_key, $this->table);

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

		if (empty($body)) {
			$response->getBody()->write(
				json_encode(['error' => 'Содержимое POST-запроса не может быть пустым.'],
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
			$id = filter_var(
				$args['id'],
				FILTER_VALIDATE_INT
			);
			if (!$id) {
				$response->getBody()->write(
					json_encode(['error' => 'ID не может быть пустым!'],
								JSON_UNESCAPED_UNICODE)
				);
				return $response->withHeader(
					'Content-Type',
					'application/json; charset=UTF-8'
				)->withStatus(400);
			}

			$values = [];

			foreach ($body as $name => $value) {
				$postData = $this->findPossibleData($name);

				if (!$postData) {
					continue;
				}

				$values[] = "{$name} = " . defType(checkLength($value, $postData['length']), $postData['type']);
			}

			$values = implode(', ', $values);

			$sql = "UPDATE {$this->prefix}_{$this->table} SET {$values} WHERE id = :id";
			$this->db->query($sql, ['id' => $id]);

			// Почему я не люблю MySQL? Потому что нельзя вернуть данные сразу после добавления в базу данных!
			// All Heil PostgreSQL! `INSERT INTO xxx (yyy) VALUES (zzz) RETURNING *`! Вот так просто!
			// Но нет, в MySQL нужно строить такой костыль!!!
			$sql  = "SELECT * FROM {$this->prefix}_{$this->table} WHERE id = :id";
			$data = $this->db->row(
				$sql,
				['id' => $id]
			);

			$cache = new CacheSystem($this->table, $sql);
			$cache->clear($this->table);
			$cache->setData($data);

			$response->getBody()->write($data);
		} else {
			$response->getBody()->write(
				json_encode(['error' => "У вас нет прав на изменение данных!"])
			);
			return $response->withHeader(
				'Content-Type',
				'application/json; charset=UTF-8'
			)->withStatus(405);
		}

		return $response->withHeader(
			'Content-Type',
			'application/json; charset=UTF-8'
		)->withStatus(201);
	}

	/**
	 * Обрабатывает удаление записи из базы данных по ID, полученному из запроса.
	 *
	 * @param ServerRequestInterface $request  Входящий HTTP-запрос.
	 * @param ResponseInterface      $response Ответ HTTP.
	 * @param array                  $args     Ассоциативный массив аргументов, переданных маршрутом, должен содержать
	 *                                         ключ 'id'.
	 *
	 * @return ResponseInterface Возвращает HTTP-ответ с результатом выполнения операции.
	 *
	 * @throws Exception В случае возникновения ошибок при работе с базой данных или некорректного API-ключа.
	 *
	 * Основные этапы работы метода:
	 * - Извлечение заголовков запроса и параметров (включая тело запроса);
	 * - Получение и проверка API-ключа;
	 * - Проверка прав доступа на операцию удаления;
	 * - Валидация ID записи, которая должна быть удалена;
	 * - Удаление записи из базы данных при наличии прав доступа;
	 * - Очистка кеша для таблицы после успешного удаления;
	 * - Формирование ответа с результатом выполнения операции: успешное удаление, отсутствие прав или ошибки.
	 *
	 *  Ответы:
	 *  - Код 405: Если API-ключ недействителен или недостаточно прав.
	 *  - Код 400: Если тело запроса пустое или ID записи некорректен.
	 *  - Код 201: При успешном удалении записи.
	 */
	public function handleDelete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
		$header      = $this->parseHeader($request);
		$params      = $request->getQueryParams() ?: [];
		$api_key     = $this->extractApiKey($params, $header);
		$checkAccess = checkAPI($api_key, $this->table);

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

		$this->access['full']       = $checkAccess['admin'];
		$this->access['can_delete'] = $checkAccess['delete'];

		if ($this->access['full'] || $this->access['can_delete']) {
			$id = filter_var($args['id'], FILTER_VALIDATE_INT);
			if (!$id) {
				$response->getBody()->write(
					json_encode(['error' => 'ID не может быть пустым!'], JSON_UNESCAPED_UNICODE)
				);
				return $response->withHeader(
					'Content-Type',
					'application/json; charset=UTF-8'
				)->withStatus(400);
			}

			$sql = "DELETE FROM {$this->prefix}_{$this->table} WHERE id = :id";
			$this->db->row($sql, ['id' => $id]);

			$cache = new CacheSystem($this->table, $sql);
			$cache->clear($this->table);

			$response->withStatus(200)->getBody()->write(json_encode(['success' => "Данные успешно удалены!"]));
		} else {
			$response->withStatus(405)->getBody()->write(
				json_encode(['error' => "У вас нет прав на удаление данных!"])
			);
		}

		return $response->withHeader(
			'Content-Type',
			'application/json; charset=UTF-8'
		);
	}

	/**
	 * Обрабатывает и нормализует заголовки HTTP-запроса.
	 *
	 * Метод извлекает заголовки из переданного объекта `Request`, удаляет префикс
	 * `HTTP_` из названий заголовков (если он присутствует) и преобразует их в нижний
	 * регистр. Кроме того, значения заголовков и их названия фильтруются для удаления
	 * специальных символов и предотвращения возможных уязвимостей.
	 *
	 * @param Request $request HTTP-запрос, из которого извлекаются заголовки.
	 *
	 * @return array Ассоциативный массив, где ключи — это нормализованные названия
	 * заголовков, а значения — обработанные значения заголовков.
	 */
	public function parseHeader(Request $request): array {
		$header = [];
		foreach ($request->getHeaders() as $name => $value) {
			$name          = strtolower(str_replace('HTTP_', '', filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS)));
			$header[$name] = filter_var($value[0], FILTER_SANITIZE_SPECIAL_CHARS);
		}

		return $header;
	}

	/**
	 * Разбирает параметры запроса, извлекая требуемые значения.
	 *
	 * @param array $params Массив параметров запроса, обычно из query string.
	 * @param array $header Массив параметров заголовков запроса.
	 *
	 * @return array Массив из трех значений:
	 *               - Значение параметра "orderby" (или замена по умолчанию).
	 *               - Значение параметра "sort" (или замена по умолчанию).
	 *               - Значение параметра "limit" (или null, если не указано).
	 */
	public function parseParameters(array $params, array $header): array {
		$orderBy = $this->findParameter('orderby', $this->orderBy, $params, $header);
		$sort    = $this->findParameter('sort', $this->sort, $params, $header);
		$limit   = $this->findParameter('limit', null, $params, $header);

		return [$orderBy, $sort, $limit];
	}

	/**
	 * Ищет значение параметра по заданному ключу из переданных массивов параметров.
	 *
	 * Если ключ найден, возвращает его значение. Если значение является массивом,
	 * возвращается первый элемент массива. Если ключ не найден ни в одном из массивов,
	 * возвращается значение замены.
	 *
	 * @param string      $searchKey     Ключ, который необходимо найти среди переданных массивов.
	 * @param string|null $replacement   Значение, возвращаемое по умолчанию, если ключ не найден.
	 * @param array       ...$parameters Один или несколько массивов, в которых выполняется поиск.
	 *
	 * @return string|null Значение, соответствующее найденному ключу, либо значение замены,
	 * если ключ отсутствует.
	 */
	public function findParameter(string $searchKey, ?string $replacement, ...$parameters): ?string {
		foreach ($parameters as $parameter) {
			foreach ($parameter as $key => $value) {

				if ($key === $searchKey) {
					if (is_array($value)) {
						return $value[0]; // Найден ключ, сразу возвращаем его значение
					}

					return $value; // Найден ключ, сразу возвращаем его значение}
				}
			}
		}

		return $replacement;
	}

	/**
	 * Извлекает значение API-ключа из предоставленных источников.
	 *
	 * Функция проверяет массивы, переданные как аргументы, и ищет значения по возможным ключам,
	 * таким как 'http_x_api_key', 'x_api_key', 'http_x-api-key' и 'x-api-key'.
	 * Если ключ найден, возвращается его значение. Если ключ не найден, возвращается null.
	 *
	 * @param array ...$args Массивы, в которых производится поиск API-ключа.
	 *                       Каждый элемент должен быть ассоциативным массивом.
	 *
	 * @return string|null Возвращает значение API-ключа, если найден, или null, если ключ отсутствует.
	 *
	 * @throws \InvalidArgumentException Бросается, если один из аргументов не является массивом.
	 */
	public function extractApiKey(...$args): ?string {
		$possibleKeys = [
			'http_x_api_key',
			'x_api_key',
			'http_x-api-key',
			'x-api-key'
		];

		foreach ($args as $arg) {
			foreach ($arg as $key => $value) {
				if (in_array(strtolower($key), $possibleKeys, true)) {
					if (is_array($value)) return $value[0]; // Найден ключ, сразу возвращаем его значение
					return $value;                          // Найден ключ, сразу возвращаем его значение
				}
			}
		}

		return null; // Возвращаем пустую строку, если ключ не найден
	}

	/**
	 * Строит массив фильтров на основе предоставленных заголовков.
	 *
	 * Этот метод обрабатывает массив заголовков, выполняя их фильтрацию и проверяя
	 * наличие поддержки фильтруемых данных в списке разрешенных полей (`$allowedFields`).
	 * Если поле разрешено, применяется преобразование значения заголовка и
	 * компаратор с использованием функции `getComparer`.
	 *
	 * @param array $headers Ассоциативный массив заголовков, где ключ является именем заголовка,
	 *                       а значение - значением заголовка.
	 *
	 * @return array Массив фильтров, которые будут использоваться для дальнейших операций,
	 *               например, в SQL-запросах.
	 */
	public function buildFilters(array $headers): array {
		$filters = [];

		foreach ($headers as $key => $values) {
			$key   = filter_var($key, FILTER_SANITIZE_SPECIAL_CHARS | CASE_LOWER);
			$value = filter_var($values, FILTER_SANITIZE_SPECIAL_CHARS);

			$dbData = $this->findPossibleData($key);

			if (!$dbData) continue;

			$filters[] = (string)($key) . getComparer($value, $dbData['type']);
		}

		return $filters;
	}

	/**
	 * Строит фильтры для ограничений доступа к данным.
	 *
	 * На основе списка собственных полей (`ownFields`) создает массив условий
	 * фильтрации в зависимости от прав доступа, указанных в свойстве класса `access`.
	 * Если включен режим "own_only" и пользователь имеет доступ только к своим данным,
	 * создаются фильтры, которые ограничивают данные по идентификатору (user_id)
	 * или имени пользователя (user_name).
	 *
	 * @return array Массив строк, каждая из которых является SQL-условием для фильтрации данных.
	 */
	public function buildAccessFilters(): array {
		$filters = [];

		foreach ($this->ownFields as $key) {
			$key = filter_var($key, FILTER_SANITIZE_SPECIAL_CHARS);

			$dbData = $this->findPossibleData($key);

			if (!$dbData) continue;

			// Если "own_only", исключить данные
			if (!$this->access['full'] && $this->access['own_only']['access']) {
				if ($dbData['type'] === 'string') {
					$filters[] = "{$key} = '{$this->access['own_only']['user_name']}'";
				} else if ($dbData['type'] === 'integer') {
					$filters[] = "{$key} = {$this->access['own_only']['user_id']}";
				}
			}

		}

		return $filters;
	}

	/**
	 * Ищет в массиве `allowedFields` элемент, который соответствует переданному имени.
	 *
	 * Если найден элемент, возвращает его в виде массива. Если совпадения не найдены,
	 * возвращает `null`.
	 *
	 * @param string $name Имя, по которому будет производиться поиск.
	 *
	 * @return array|null Найденный массив данных или `null`, если совпадений нет.
	 */
	public function findPossibleData(string $name): ?array {
		foreach ($this->allowedFields as $data) {
			if ($data['name'] === $name) {
				return $data; // Вернуть найденный элемент
			}
		}

		return null; // Вернуть null, если ничего не найдено
	}
}
