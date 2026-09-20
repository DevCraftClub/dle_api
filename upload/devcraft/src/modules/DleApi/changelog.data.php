<?php

declare(strict_types=1);

use DevCraft\Builders\ChangelogBuilder;
use DevCraft\Types\Changelog;

/**
 * Журнал изменений DLE API (fluent ChangelogBuilder).
 *
 * @return list<Changelog>
 */
return [
	ChangelogBuilder::create('200.1.1')
		->date('2026-09-20')
		->changed([
			__('Зависимость: DevCraft Admin ≥ 200.4.1 для авто-создания таблиц api_* после установки ядра.'),
			__('In-process SDK (Schema / Fluent / Xfield / DcApi) переехал в DevCraft Admin (devcraft/src/sdk/dle/); пакет API стал его потребителем. Фасад DcApi и контракт /api/v2 не изменились.'),
			__('Namespace SDK: DleApi\{Schema,Fluent,Xfield,Sdk} → DevCraft\Dle\... Старые имена работают через алиасы классов (уйдут в следующем мажоре). Namespace DleApi\Http и DleApi\OpenApi остались в пакете API.'),
			__('install.xml: иконка — путь к Public/icon.png, allow_groups 1,2, notice — страница плагина и документация.'),
			__('Блок профиля: Controller/show_dleapi_profile.php; CSS/JS — Public/ + siteAssets на userinfo.'),
		])
		->removed([
			__('api/sdk/bootstrap.php и вставки в engine/init.php: SDK поднимается через devcraft/init.php.'),
			__('Файл engine/modules/devcraft/dleapi_profile.php и CSS/JS профиля в теме.'),
			__('Автопосев dleapi.json при открытии настроек, если файла нет.'),
		])
		->fixed([
			__('При SQLSTATE[42S02] на api_*: нужен DevCraft Admin ≥ 200.4.1 (GenerateMigrations при пересборке схемы); сброс cycle_orm_schema.ser и повторный заход в модуль.'),
		])
		->build(),
	ChangelogBuilder::create('200.1.0')
		->date('2026-07-21')
		->added([
			__('API v2 (/api/v2) на CycleORM из DevCraft Admin.'),
			__('OAuth2 Authorization Server: authorize, token, revoke; доступ только через Bearer.'),
			__('credential_type api_key|auth|oauth_client; ресурсы только AuthToken; GET /me и /oauth/userinfo.'),
			__('OAuth AS discovery, authorize по сессии DLE, копируемые endpoint URL.'),
			__('Уровни доступа, синхронизация с группами, заявки на ключ, профиль (public AJAX).'),
			__('DEMO_MODE / DLEAPI_SECURE из .env; email-шаблоны через install.xml.'),
			__('Fluent-билдеры prepareNewPost / prepareNewUser / preparePlugin / prepareNewUsergroup (in-process и HTTP SDK).'),
			__('Каталог Schema таблиц DLE в api/includes/Schemas.'),
			__('OpenAPI (swagger-php) и документация OAD в mhdocs.'),
			__('Загрузка файлов через штатный пайплайн DLE.'),
			__('Фильтр постов по одной категории при нескольких (FIND_IN_SET / issue #12).'),
			__('Админ-модуль DleApi для DevCraft Admin ≥ 200.4.0 (только конфигурация).'),
			__('HTTP /table/{name}: интроспекция таблиц вне SchemaRegistry + TableScopeGuard.'),
			__('SDK facades: DcApi::news() / user() / comment() / conversation() / plugin() / file() / staticPage() / schema().'),
			__('Fluent CRUD на AbstractTableSchema (with / create / save / delete / filter / fromArray).'),
			__('GET-кэш TableQuery через CacheControl (dle_api_query, TTL).'),
			__('Админка: multi получатели уведомлений, WYSIWYG для PM; panel-title через caption.'),
			__('Админка: FormPanel ключей/уровней/OAuth свёрнут на узком экране (Metro d-none / d-block-md).'),
		])
		->changed([
			__('Совместимость с DLE 20.0 (xfields.json, dual category, conversations).'),
			__('Таблицы api_keys / api_scope / api_access_levels / OAuth создаются Cycle-миграциями DevCraft (Models + AbstractEntity), а не DDL в install.xml.'),
			__('Scopes: колонка edit; own_only/cheater на уровне; trailing slash на всех маршрутах v2.'),
			__('OpenAPI: описание ApiError без PHPDoc {@see}; Schema без пометки install.php.'),
		])
		->removed([
			__('API v1 (Slim + Illuminate Capsule, X-Api-Key) полностью удалён.'),
			__('Приём сырого API-ключа как Bearer на ресурсах /table.'),
		])
		->build(),
	ChangelogBuilder::create('0.3.0')
		->changed([
			__('Оптимизация кода под PHP 8.2'),
			__('Минимально поддерживаемая версия DLE - 16.0'),
			__('Изменён список алгоритмов на hash_hmac_algos'),
		])
		->build(),
	ChangelogBuilder::create('0.2.1')
		->changed([
			__('Убрана процедура проверки атрибута в таблице'),
		])
		->build(),
	ChangelogBuilder::create('0.2.0')
		->changed([
			__('Оптимизирован код'),
			__('Генерация ключа привязана к времени'),
			__('Добавлена возможность добавлять ключи независимо от пользователя => для этого нужно выбрать в меню пользователя "Гость / Неавторизованный"'),
			__('Добавлена возможность генерировать свои рутеры на основе базы данных => читайте документацию'),
			__('Обновлены данные для таблиц базы данных'),
			__('Обновлена документация API'),
			__('Минимальные требования к серверу поднял до минимально требуемой версии от DLE-News -> 7.4, возможно не будет работать на версиях ниже 15.х'),
		])
		->build(),
	ChangelogBuilder::create('0.1.4')
		->changed([
			__('Безопасный вывод данных (IP, пароли и хэш суммы)'),
			__('Добавлена функция вывода только принадлежащих API-ключу записей'),
			__('Добавлена функция массовых действий (удаление, (де-)активация, снятие / добавление ограничение, снятие / удаление администраторских ограничений)'),
		])
		->build(),
	ChangelogBuilder::create('0.1.3')
		->changed([
			__('Для удаления новостей была использована функция движка'),
			__('Для удаления комментариев была использована функция движка'),
			__('Для удаления новостей была использована функция движка'),
			__('Изменил нумерование версий'),
			__('composer настроил под версию PHP 5.6'),
		])
		->build(),
	ChangelogBuilder::create('0.0.2')
		->changed([
			__('Были исправлены несколько багов, спасибо @jyarali'),
			__('Исправлена проверка ключей в базе данных'),
			__('Добавлена проверка по длине значения для типа "string"'),
			__('Массивы с ячейками таблиц были обновлены до значений DLE 14.1 (на ранние версии DLE это никак не влияет)'),
			__('Для пользователей был использован штатный API класс самой DLE, чтобы авторизовать и регистрировать пользователей'),
			__('Для авторизации пользователей нужно при помощи метода POST указать следующий путь URL: api/v1/users/auth. В заголовке обязательно должны быть значения имя пользователя и его пароль в незакодированном виде.'),
			__('Для регистрации пользователя нужно при помощи метода POST указать следующий путь URL: api/v1/users/register. В заголовке нужно указать имя пользователя, пароль, электронную почту и ID группы пользователей.'),
			__('При регистрации и авторизации возвращается массив данных об этом пользователе'),
		])
		->build(),
	ChangelogBuilder::create('0.0.1')
		->changed([
			__('Первая стандартная версия'),
		])
		->build(),
];
