<?php

declare(strict_types=1);

namespace DleApi\OpenApi;

use DleApi\Schema\ConversationsSchema;
use DleApi\Schema\PluginsSchema;
use DleApi\Schema\PostSchema;
use DleApi\Schema\TableRowUnion;
use DleApi\Schema\UsergroupsSchema;
use DleApi\Schema\UsersSchema;
use DleApi\Xfield\Schema\PostXfieldField;
use DleApi\Xfield\Schema\PostXfieldsCatalog;
use DleApi\Xfield\Schema\UserXfieldsCatalog;
use OpenApi\Attributes as OA;

/**
 * Корневое описание OpenAPI для DLE API v2.
 */
#[OA\Info(
	version: '200.2.0',
	title: 'DLE API',
	description: 'Неофициальное REST API для DataLife Engine 20.0. Поверхность: /api/v2. Ресурсы: Authorization: Bearer <AuthToken>. Выдача: POST /oauth/token с credential_type=api_key|auth|oauth_client или grant_type. Discovery: /.well-known/oauth-authorization-server. Identity: GET /me и /oauth/userinfo.',
)]
#[OA\Server(
	url: '{apiBase}',
	description: 'Базовый URL API (подставьте свой хост, напр. https://example.com/api/v2)',
	variables: [
		new OA\ServerVariable(
			serverVariable: 'apiBase',
			default: '/api/v2',
			description: 'Путь или полный URL до /api/v2',
		),
	],
)]
#[OA\Server(url: '/api/v2', description: 'Относительный путь по умолчанию')]
#[OA\SecurityScheme(
	securityScheme: 'bearerAuth',
	type: 'http',
	scheme: 'bearer',
	bearerFormat: 'AuthToken',
	description: 'Access token из POST /oauth/token (credential_type или grant_type). Сырой API-ключ на ресурсах не принимается.',
)]
#[OA\Tag(name: 'OAuth', description: 'Выдача и отзыв токенов, discovery, userinfo')]
#[OA\Tag(name: 'Me', description: 'Субъект AuthToken')]
#[OA\Tag(name: 'Table', description: 'Универсальный CRUD по SchemaRegistry: GET/POST /table/{name}/, GET/PUT/DELETE /table/{name}/{id}')]
#[OA\Tag(name: 'Post', description: 'Новости')]
#[OA\Tag(name: 'User', description: 'Пользователи и группы')]
#[OA\Tag(name: 'Plugin', description: 'Плагины')]
#[OA\Tag(name: 'Upload', description: 'Загрузка файлов')]
#[OA\Tag(name: 'Xfield', description: 'Каталог доп. полей (xfields.json / userxfields.json)')]
#[OA\Tag(name: 'System', description: 'Служебные')]
final class Document {
	#[OA\Post(
		path: '/oauth/token',
		operationId: 'oauthToken',
		summary: 'Выдача AuthToken (credential_type или grant_type)',
		description: 'Предпочтительно credential_type=api_key|auth|oauth_client. Альтернатива: grant_type=client_credentials|password|authorization_code|refresh_token. В DEMO_MODE при успехе — authorized без access_token.',
		security: [],
		tags: ['OAuth'],
	)]
	#[OA\RequestBody(
		required: true,
		description: 'Параметры credential_type или OAuth2 grant',
		content: new OA\JsonContent(
			properties: [
				new OA\Property(
					property: 'credential_type',
					type: 'string',
					enum: ['api_key', 'auth', 'oauth_client'],
					description: 'Предпочтительный способ выдачи AuthToken',
				),
				new OA\Property(
					property: 'grant_type',
					type: 'string',
					enum: ['client_credentials', 'authorization_code', 'refresh_token', 'password'],
				),
				new OA\Property(property: 'api_key', type: 'string', description: 'credential_type=api_key'),
				new OA\Property(property: 'client_id', type: 'string'),
				new OA\Property(property: 'client_secret', type: 'string'),
				new OA\Property(property: 'scope', type: 'string'),
				new OA\Property(property: 'code', type: 'string'),
				new OA\Property(property: 'redirect_uri', type: 'string'),
				new OA\Property(property: 'code_verifier', type: 'string'),
				new OA\Property(property: 'refresh_token', type: 'string'),
				new OA\Property(property: 'username', type: 'string', description: 'Логин или email (auth / password)'),
				new OA\Property(property: 'password', type: 'string'),
			],
			example: [
				'credential_type' => 'api_key',
				'api_key'         => 'your_api_key',
			],
		),
	)]
	#[OA\Response(
		response: 200,
		description: 'Токен выдан или DEMO_MODE authorized',
		content: new OA\JsonContent(
			properties: [
				new OA\Property(property: 'access_token', type: 'string', nullable: true),
				new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
				new OA\Property(property: 'expires_in', type: 'integer'),
				new OA\Property(property: 'refresh_token', type: 'string'),
				new OA\Property(property: 'demo_mode', type: 'boolean'),
				new OA\Property(property: 'authorized', type: 'boolean'),
				new OA\Property(property: 'message', type: 'string'),
			],
		),
	)]
	#[OA\Response(response: 400, description: 'Ошибка grant', content: new OA\JsonContent(
		properties: [
			new OA\Property(property: 'error', type: 'string'),
			new OA\Property(property: 'message', type: 'string'),
		],
	))]
	public function oauthToken(): void {}

	#[OA\Get(
		path: '/.well-known/oauth-authorization-server',
		operationId: 'oauthDiscovery',
		summary: 'OAuth AS discovery (без Bearer)',
		security: [],
		tags: ['OAuth'],
	)]
	#[OA\Response(response: 200, description: 'Метаданные AS')]
	public function oauthDiscovery(): void {}

	#[OA\Get(
		path: '/me',
		operationId: 'me',
		summary: 'Субъект AuthToken (identity без CRUD-масок ПДн)',
		security: [['bearerAuth' => []]],
		tags: ['Me'],
	)]
	#[OA\Response(response: 200, description: 'Identity')]
	public function me(): void {}

	#[OA\Get(
		path: '/oauth/userinfo',
		operationId: 'oauthUserinfo',
		summary: 'OIDC-совместимый userinfo (тот же payload, что /me)',
		security: [['bearerAuth' => []]],
		tags: ['OAuth'],
	)]
	#[OA\Response(response: 200, description: 'Identity')]
	public function oauthUserinfo(): void {}

	#[OA\Post(
		path: '/oauth/revoke',
		operationId: 'oauthRevoke',
		summary: 'Отзыв токена',
		security: [],
		tags: ['OAuth'],
	)]
	#[OA\RequestBody(
		required: true,
		content: new OA\JsonContent(
			required: ['token'],
			properties: [
				new OA\Property(property: 'token', type: 'string', description: 'Access или refresh token'),
			],
		),
	)]
	#[OA\Response(
		response: 200,
		description: 'Отозван',
		content: new OA\JsonContent(
			properties: [
				new OA\Property(property: 'revoked', type: 'boolean', example: true),
			],
		),
	)]
	public function oauthRevoke(): void {}

	#[OA\Get(
		path: '/post/',
		operationId: 'listPosts',
		summary: 'Список новостей (фильтры как у /table/post/ + legacy headers)',
		description: 'Query-параметры колонок схемы; xf[name]=value для доп. полей; ! и % префиксы значений. Legacy: HTTP-заголовки с именами колонок (BC). category — virtual FK csv + post_extras_cats.',
		security: [['bearerAuth' => []]],
		tags: ['Post'],
	)]
	#[OA\QueryParameter(name: 'limit', schema: new OA\Schema(type: 'integer', default: 20))]
	#[OA\QueryParameter(name: 'offset', schema: new OA\Schema(type: 'integer', default: 0))]
	#[OA\QueryParameter(name: 'orderby', description: 'Колонка сортировки', schema: new OA\Schema(type: 'string'))]
	#[OA\QueryParameter(name: 'sort', schema: new OA\Schema(type: 'string', enum: ['ASC', 'DESC'], default: 'DESC'))]
	#[OA\QueryParameter(name: 'category', description: 'Virtual FK csv → category.id (FIND_IN_SET + post_extras_cats). !negate, %LIKE', schema: new OA\Schema(type: 'string'))]
	#[OA\QueryParameter(name: 'approve', schema: new OA\Schema(type: 'string'))]
	#[OA\QueryParameter(
		name: 'xf',
		description: 'Доп. поля: xf[field]=value (pad-LIKE по xfields). Пример: xf[title]=foo',
		style: 'form',
		explode: true,
		schema: new OA\Schema(type: 'object', additionalProperties: new OA\AdditionalProperties(type: 'string')),
	)]
	#[OA\Response(
		response: 200,
		description: 'OK',
		content: new OA\JsonContent(
			type: 'array',
			items: new OA\Items(ref: PostSchema::class),
		),
	)]
	#[OA\Response(response: 401, description: 'Требуется Bearer')]
	public function listPosts(): void {}

	#[OA\Get(
		path: '/post/{id}',
		operationId: 'getPost',
		summary: 'Новость по id',
		security: [['bearerAuth' => []]],
		tags: ['Post'],
	)]
	#[OA\PathParameter(name: 'id', required: true, schema: new OA\Schema(type: 'integer'))]
	#[OA\Response(
		response: 200,
		description: 'OK',
		content: [new OA\MediaType(
			mediaType: 'application/json',
			schema: new OA\Schema(ref: PostSchema::class),
		)],
	)]
	#[OA\Response(response: 401, description: 'Требуется Bearer')]
	#[OA\Response(response: 404, description: 'Не найдено')]
	public function getPost(): void {}

	#[OA\Get(
		path: '/table/{name}',
		operationId: 'listTableRows',
		summary: 'Список записей таблицы с фильтрами',
		description: 'Любая таблица SchemaRegistry. Фильтры = query-параметры колонок. Virtual FK (RelationMap): csv→FIND_IN_SET, one→=. Операторы: !negate, %LIKE. Пример: /table/banners/?category=1&approve=1. xf[name] если есть колонка xfields.',
		security: [['bearerAuth' => []]],
		tags: ['Table'],
	)]
	#[OA\PathParameter(
		name: 'name',
		required: true,
		description: 'Логическое имя таблицы SchemaRegistry (banners, post, category, …)',
		schema: new OA\Schema(type: 'string', description: 'Логическое имя таблицы SchemaRegistry'),
	)]
	#[OA\QueryParameter(name: 'limit', schema: new OA\Schema(type: 'integer', default: 20))]
	#[OA\QueryParameter(name: 'offset', schema: new OA\Schema(type: 'integer', default: 0))]
	#[OA\QueryParameter(name: 'orderby', description: 'Колонка сортировки (из схемы)', schema: new OA\Schema(type: 'string'))]
	#[OA\QueryParameter(name: 'sort', schema: new OA\Schema(type: 'string', enum: ['ASC', 'DESC'], default: 'DESC'))]
	#[OA\QueryParameter(
		name: 'xf',
		description: 'Доп. поля (если у таблицы есть xfields): xf[field]=value. Пример: xf[title]=foo',
		style: 'form',
		explode: true,
		schema: new OA\Schema(type: 'object', additionalProperties: new OA\AdditionalProperties(type: 'string')),
	)]
	#[OA\Response(
		response: 200,
		description: 'OK',
		content: new OA\JsonContent(
			properties: [
				new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: TableRowUnion::class)),
				new OA\Property(property: 'count', type: 'integer'),
				new OA\Property(property: 'table', type: 'string'),
			],
		),
	)]
	#[OA\Response(response: 401, description: 'Требуется Bearer')]
	#[OA\Response(response: 404, description: 'Неизвестная таблица')]
	#[OA\Response(response: 422, description: 'Неизвестная колонка / ошибка фильтра')]
	public function listTableRows(): void {}

	#[OA\Get(
		path: '/table/{name}/{id}',
		operationId: 'getTableRow',
		summary: 'Запись по скалярному PK',
		security: [['bearerAuth' => []]],
		tags: ['Table'],
	)]
	#[OA\PathParameter(
		name: 'name',
		required: true,
		schema: new OA\Schema(type: 'string', description: 'Логическое имя таблицы SchemaRegistry'),
	)]
	#[OA\PathParameter(name: 'id', required: true, description: 'Значение первичного ключа', schema: new OA\Schema(type: 'string'))]
	#[OA\Response(
		response: 200,
		description: 'OK',
		content: new OA\JsonContent(
			properties: [
				new OA\Property(property: 'data', ref: TableRowUnion::class),
				new OA\Property(property: 'table', type: 'string'),
			],
		),
	)]
	#[OA\Response(response: 400, description: 'Составной PK не поддерживается через /{id}')]
	#[OA\Response(response: 401, description: 'Требуется Bearer')]
	#[OA\Response(response: 404, description: 'Не найдено')]
	public function getTableRow(): void {}

	#[OA\Post(
		path: '/table/{name}',
		operationId: 'createTableRow',
		summary: 'Создание записи (Fluent prepare)',
		security: [['bearerAuth' => []]],
		tags: ['Table'],
	)]
	#[OA\PathParameter(
		name: 'name',
		required: true,
		description: 'Логическое имя таблицы SchemaRegistry',
		schema: new OA\Schema(type: 'string', description: 'Логическое имя таблицы SchemaRegistry'),
	)]
	#[OA\RequestBody(
		required: true,
		description: 'Колонки выбранной таблицы (+ optional nested children). Схема = oneOf TableRow.',
		content: [new OA\MediaType(
			mediaType: 'application/json',
			schema: new OA\Schema(ref: TableRowUnion::class),
		)],
	)]
	#[OA\Response(
		response: 201,
		description: 'Создано',
		content: new OA\JsonContent(
			properties: [
				new OA\Property(property: 'id', type: 'integer'),
				new OA\Property(property: 'table', type: 'string'),
			],
		),
	)]
	#[OA\Response(response: 401, description: 'Требуется Bearer')]
	#[OA\Response(response: 422, description: 'Ошибка создания')]
	public function createTableRow(): void {}

	#[OA\Put(
		path: '/table/{name}/{id}',
		operationId: 'updateTableRow',
		summary: 'Обновление записи по скалярному PK',
		security: [['bearerAuth' => []]],
		tags: ['Table'],
	)]
	#[OA\PathParameter(
		name: 'name',
		required: true,
		schema: new OA\Schema(type: 'string', description: 'Логическое имя таблицы SchemaRegistry'),
	)]
	#[OA\PathParameter(name: 'id', required: true, schema: new OA\Schema(type: 'string'))]
	#[OA\RequestBody(
		required: true,
		content: [new OA\MediaType(
			mediaType: 'application/json',
			schema: new OA\Schema(ref: TableRowUnion::class),
		)],
	)]
	#[OA\Response(
		response: 200,
		description: 'Обновлено',
		content: new OA\JsonContent(
			properties: [
				new OA\Property(property: 'id', type: 'string'),
				new OA\Property(property: 'table', type: 'string'),
				new OA\Property(property: 'updated', type: 'boolean'),
			],
		),
	)]
	#[OA\Response(response: 400, description: 'Составной PK не поддерживается')]
	#[OA\Response(response: 401, description: 'Требуется Bearer')]
	#[OA\Response(response: 404, description: 'Не найдено')]
	#[OA\Response(response: 422, description: 'Ошибка обновления')]
	public function updateTableRow(): void {}

	#[OA\Delete(
		path: '/table/{name}/{id}',
		operationId: 'deleteTableRow',
		summary: 'Удаление записи по скалярному PK',
		security: [['bearerAuth' => []]],
		tags: ['Table'],
	)]
	#[OA\PathParameter(
		name: 'name',
		required: true,
		schema: new OA\Schema(type: 'string', description: 'Логическое имя таблицы SchemaRegistry'),
	)]
	#[OA\PathParameter(name: 'id', required: true, schema: new OA\Schema(type: 'string'))]
	#[OA\Response(
		response: 200,
		description: 'Удалено',
		content: new OA\JsonContent(
			properties: [
				new OA\Property(property: 'id', type: 'string'),
				new OA\Property(property: 'table', type: 'string'),
				new OA\Property(property: 'deleted', type: 'boolean'),
			],
		),
	)]
	#[OA\Response(response: 400, description: 'Составной PK не поддерживается')]
	#[OA\Response(response: 401, description: 'Требуется Bearer')]
	#[OA\Response(response: 404, description: 'Не найдено')]
	public function deleteTableRow(): void {}

	#[OA\Post(
		path: '/post/',
		operationId: 'createPost',
		summary: 'Создание новости (sugar → prepare post)',
		security: [['bearerAuth' => []]],
		tags: ['Post'],
	)]
	#[OA\RequestBody(
		description: 'Новость + optional nested children',
		required: true,
		content: [new OA\MediaType(
			mediaType: 'application/json',
			schema: new OA\Schema(ref: PostSchema::class),
		)],
	)]
	#[OA\Response(
		response: 201,
		description: 'Создано',
		content: [new OA\MediaType(
			mediaType: 'application/json',
			schema: new OA\Schema(properties: [
				new OA\Property(property: 'id', type: 'integer'),
			]),
		)],
	)]
	#[OA\Response(response: 401, description: 'Требуется Bearer')]
	#[OA\Response(response: 422, description: 'Ошибка создания')]
	public function createPost(): void {}

	#[OA\Post(
		path: '/user/',
		operationId: 'createUser',
		summary: 'Создание пользователя',
		security: [['bearerAuth' => []]],
		tags: ['User'],
	)]
	#[OA\RequestBody(
		required: true,
		content: new OA\JsonContent(ref: UsersSchema::class),
	)]
	#[OA\Response(
		response: 201,
		description: 'Создано',
		content: new OA\JsonContent(properties: [
			new OA\Property(property: 'id', type: 'integer'),
		]),
	)]
	#[OA\Response(response: 401, description: 'Требуется Bearer')]
	#[OA\Response(response: 422, description: 'Ошибка создания')]
	public function createUser(): void {}

	#[OA\Post(
		path: '/usergroup/',
		operationId: 'createUsergroup',
		summary: 'Создание группы пользователей',
		security: [['bearerAuth' => []]],
		tags: ['User'],
	)]
	#[OA\RequestBody(
		required: true,
		content: new OA\JsonContent(ref: UsergroupsSchema::class),
	)]
	#[OA\Response(
		response: 201,
		description: 'Создано',
		content: new OA\JsonContent(properties: [
			new OA\Property(property: 'id', type: 'integer'),
		]),
	)]
	#[OA\Response(response: 401, description: 'Требуется Bearer')]
	#[OA\Response(response: 422, description: 'Ошибка создания')]
	public function createUsergroup(): void {}

	#[OA\Post(
		path: '/plugin/',
		operationId: 'createPlugin',
		summary: 'Регистрация плагина',
		security: [['bearerAuth' => []]],
		tags: ['Plugin'],
	)]
	#[OA\RequestBody(
		required: true,
		content: new OA\JsonContent(ref: PluginsSchema::class),
	)]
	#[OA\Response(
		response: 201,
		description: 'Создано',
		content: new OA\JsonContent(properties: [
			new OA\Property(property: 'id', type: 'integer'),
		]),
	)]
	#[OA\Response(response: 401, description: 'Требуется Bearer')]
	#[OA\Response(response: 422, description: 'Ошибка создания')]
	public function createPlugin(): void {}

	#[OA\Post(
		path: '/upload/',
		operationId: 'uploadFile',
		summary: 'Загрузка файла (multipart)',
		security: [['bearerAuth' => []]],
		tags: ['Upload'],
	)]
	#[OA\RequestBody(
		required: true,
		content: [new OA\MediaType(
			mediaType: 'multipart/form-data',
			schema: new OA\Schema(
				required: ['file'],
				properties: [
					new OA\Property(property: 'file', type: 'string', format: 'binary'),
				],
			),
		)],
	)]
	#[OA\Response(
		response: 201,
		description: 'Загружено',
		content: new OA\JsonContent(type: 'object'),
	)]
	#[OA\Response(response: 401, description: 'Требуется Bearer')]
	public function uploadFile(): void {}

	#[OA\Get(
		path: '/conversations/',
		operationId: 'listConversations',
		summary: 'Список переписок',
		security: [['bearerAuth' => []]],
		tags: ['System'],
	)]
	#[OA\Response(
		response: 200,
		description: 'OK',
		content: new OA\JsonContent(
			type: 'array',
			items: new OA\Items(ref: ConversationsSchema::class),
		),
	)]
	#[OA\Response(response: 401, description: 'Требуется Bearer')]
	public function listConversations(): void {}

	#[OA\Get(
		path: '/xfields/{scope}/',
		operationId: 'listXfields',
		summary: 'Каталог доп. полей',
		security: [['bearerAuth' => []]],
		tags: ['Xfield'],
	)]
	#[OA\PathParameter(name: 'scope', required: true, schema: new OA\Schema(type: 'string', enum: ['post', 'user']))]
	#[OA\Response(
		response: 200,
		description: 'OK (post → PostXfieldsCatalog, user → UserXfieldsCatalog)',
		content: new OA\JsonContent(
			oneOf: [
				new OA\Schema(ref: PostXfieldsCatalog::class),
				new OA\Schema(ref: UserXfieldsCatalog::class),
			],
		),
	)]
	#[OA\Response(response: 401, description: 'Требуется Bearer')]
	public function listXfields(): void {}

	#[OA\Post(
		path: '/xfields/{scope}/encode',
		operationId: 'encodeXfields',
		summary: 'Сериализация значений в формат post.xfields / users.xfields (аналог forPost)',
		description: 'Body: {fields:{name:value}} или {name,value}. Ответ: raw + parsed.',
		security: [['bearerAuth' => []]],
		tags: ['Xfield'],
	)]
	#[OA\PathParameter(name: 'scope', required: true, schema: new OA\Schema(type: 'string', enum: ['post', 'user']))]
	#[OA\RequestBody(required: true, content: new OA\JsonContent(type: 'object'))]
	#[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'object'))]
	#[OA\Response(response: 422, description: 'Неизвестное поле / validation')]
	public function encodeXfields(): void {}

	#[OA\Get(
		path: '/xfields/{scope}/{name}',
		operationId: 'getXfield',
		summary: 'Определение одного поля',
		security: [['bearerAuth' => []]],
		tags: ['Xfield'],
	)]
	#[OA\PathParameter(name: 'scope', required: true, schema: new OA\Schema(type: 'string', enum: ['post', 'user']))]
	#[OA\PathParameter(name: 'name', required: true, schema: new OA\Schema(type: 'string'))]
	#[OA\QueryParameter(name: 'as', description: 'Projection по типу (image, text, …)', schema: new OA\Schema(type: 'string'))]
	#[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PostXfieldField::class))]
	#[OA\Response(response: 404, description: 'Не найдено')]
	#[OA\Response(response: 422, description: 'Тип не совпадает с ?as=')]
	public function getXfield(): void {}

	#[OA\Post(
		path: '/xfields/{scope}/',
		operationId: 'createXfield',
		summary: 'Создать определение поля (typed validation)',
		security: [['bearerAuth' => []]],
		tags: ['Xfield'],
	)]
	#[OA\PathParameter(name: 'scope', required: true, schema: new OA\Schema(type: 'string', enum: ['post', 'user']))]
	#[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PostXfieldField::class))]
	#[OA\Response(response: 201, description: 'Создано')]
	#[OA\Response(response: 422, description: 'validation + details.fields')]
	public function createXfield(): void {}

	#[OA\Put(
		path: '/xfields/{scope}/{name}',
		operationId: 'replaceXfield',
		summary: 'Заменить определение поля',
		security: [['bearerAuth' => []]],
		tags: ['Xfield'],
	)]
	#[OA\PathParameter(name: 'scope', required: true, schema: new OA\Schema(type: 'string', enum: ['post', 'user']))]
	#[OA\PathParameter(name: 'name', required: true, schema: new OA\Schema(type: 'string'))]
	#[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PostXfieldField::class))]
	#[OA\Response(response: 200, description: 'Обновлено')]
	#[OA\Response(response: 422, description: 'validation + details.fields')]
	public function replaceXfield(): void {}

	#[OA\Delete(
		path: '/xfields/{scope}/{name}',
		operationId: 'deleteXfield',
		summary: 'Удалить определение поля',
		security: [['bearerAuth' => []]],
		tags: ['Xfield'],
	)]
	#[OA\PathParameter(name: 'scope', required: true, schema: new OA\Schema(type: 'string', enum: ['post', 'user']))]
	#[OA\PathParameter(name: 'name', required: true, schema: new OA\Schema(type: 'string'))]
	#[OA\Response(response: 200, description: 'Удалено')]
	public function deleteXfield(): void {}

	#[OA\Get(
		path: '/health',
		operationId: 'health',
		summary: 'Проверка API (без Bearer)',
		security: [],
		tags: ['System'],
	)]
	#[OA\Response(
		response: 200,
		description: 'OK',
		content: new OA\JsonContent(
			properties: [
				new OA\Property(property: 'version', type: 'string', example: '200.1.0'),
				new OA\Property(property: 'api', type: 'string', example: 'v2'),
				new OA\Property(property: 'auth', type: 'string', example: 'Bearer'),
			],
		),
	)]
	public function health(): void {}
}
