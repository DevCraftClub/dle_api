[![DLE-20.0](https://img.shields.io/badge/DLE-20.0-green.svg?style=flat-square)](https://dle-news.ru/)
[![PHP-8.3](https://img.shields.io/badge/PHP-8.3-red.svg?style=flat-square)](https://www.php.net/)
[![DevCraft-Admin](https://img.shields.io/badge/DevCraft%20Admin-%E2%89%A5200.4.0-blue.svg?style=flat-square)](https://readme.devcraft.club/dev/devcraft_admin/install/)
![Version](https://img.shields.io/badge/Version-200.1.0-orange.svg?style=flat-square)

# DLE API

Неофициальное REST API для DataLife Engine **20.0**.

<<<<<<< New base: Update README.md
HTTP-поверхность: **`/api/v2`** — Slim 4, OAuth2 Authorization Server (league/oauth2-server 9), Fluent Schema ORM, OpenAPI.

In-process SDK: глобальный **`DcApi`**.

- Документация: [readme.devcraft.club/dev/dle_api/](https://readme.devcraft.club/dev/dle_api/install/)
- OpenAPI: [`apidata/openapi.yaml`](apidata/openapi.yaml)

## Требования

| Компонент | Версия |
|-----------|--------|
| DLE | ≥ 20.0 |
| PHP | ≥ 8.3 |
| DevCraft Admin | ≥ 200.4.0 |

## Что нового в 200.1.0

Полный переход с API v1 (Slim + Illuminate Capsule, X-Api-Key) на **API v2**.

### Добавлено

- **OAuth2 Authorization Server**: `authorize`, `token`, `revoke`, `userinfo`; AS discovery (`/.well-known/oauth-authorization-server`)
- **Три типа выдачи токена** (`credential_type`): `api_key`, `auth` (логин/пароль DLE), `oauth_client` (client_credentials)
- **Bearer-only доступ** ко всем ресурсам; `GET /me` и `GET /oauth/userinfo` для identity
- **Авторизация через сессию DLE** — `GET /oauth/authorize` с редиректом на форму входа DLE
- **Проверка raw API-ключа** — `GET /key/check` (единственный эндпоинт с raw key вместо Bearer)
- **Уровни доступа** с синхронизацией по группам DLE, заявки на ключ, профиль (public AJAX)
- **CRUD по таблицам** — `GET/POST/PUT/DELETE /table/{name}` с интроспекцией + TableScopeGuard
- **Ресурсы**: `GET/POST /post`, `POST /user`, `POST /usergroup`, `POST /plugin`, `GET /conversations`
- **Доп. поля (xfields)**: `GET/POST/PUT/PATCH/DELETE /xfields/{scope}/{name}`, encode
- **Загрузка файлов** — `POST /upload` через штатный пайплайн DLE
- **Каталог Schema** таблиц DLE (~40 схем) в `api/src/Schema/`
- **OpenAPI** — swagger-php 5 с PHP-атрибутами; генерация через `composer openapi`
- **DEMO_MODE / DLEAPI_SECURE** из `.env`
- **SDK facades**: `DcApi::news()`, `user()`, `comment()`, `conversation()`, `plugin()`, `file()`, `staticPage()`, `schema()`
- **Fluent CRUD** на AbstractTableSchema (with / create / save / delete / filter / fromArray)
- **Админ-модуль DleApi** для DevCraft Admin: ключи, OAuth-клиенты, уровни доступа, настройки, changelog
- **Интеграционные тесты** (PHPUnit 12 + Guzzle 8) — 53 теста для всех эндпоинтов
- **CI**: Composer check, Dependabot auto-merge, composer update workflow (ежемесячно)

### Изменено

- Совместимость с DLE 20.0 (xfields.json, dual category, conversations)
- Таблицы создаются Cycle-миграциями DevCraft, а не DDL в install.xml
- Scopes: колонка `edit`; `own_only`/`cheater` на уровне; trailing slash на всех маршрутах v2

### Удалено

- API v1 (Slim + Illuminate Capsule, X-Api-Key) **полностью удалён**
- Приём сырого API-ключа как Bearer на ресурсах `/table`

## Эндпоинты `/api/v2`

### Публичные (без авторизации)

| Метод | Путь | Описание |
|-------|------|----------|
| GET | `/health` | Версия, статус |
| GET | `/.well-known/oauth-authorization-server` | OAuth2 AS discovery |
| GET, POST | `/oauth/authorize` | Авторизация (redirect flow) |
| POST | `/oauth/token` | Выдача access_token |
| POST | `/oauth/revoke` | Отзыв токена |

### Raw API Key (`Authorization: Bearer <apiKey>`)

| Метод | Путь | Описание |
|-------|------|----------|
| GET | `/key/check` | Проверка ключа, scopes, identity |
||||||| Common ancestor
# DLE API
Модификация для админпанели и глобальные функции для моих разработок
Совместимость проверенна на DLE-версиях 17.3. Для корректной работы требуется минимальная версия MySQL 5.5.6 или MariaDB 10.0, поскольку используются Foreign Key, которые требуют наличие InnoDB.
=======
Единственная HTTP-поверхность: **`/api/v2`** — CycleORM (DevCraft), OAuth2 **Bearer**, Fluent, xfields, uploads.

In-process SDK: глобальный **`DcApi`** (после enable плагина; не legacy `DLE_API`).

Документация: [readme.devcraft.club/dev/dle_api/](https://readme.devcraft.club/dev/dle_api/install/)  
OpenAPI: [`apidata/openapi.yaml`](apidata/openapi.yaml)

## Требования

| Компонент | Версия |
|-----------|--------|
| DLE | ≥ 20.0 |
| PHP | ≥ 8.3 |
| DevCraft Admin | ≥ 200.4.0 |

## Установка

1. Установите DevCraft Admin.
2. Упакуйте `upload/` и установите через менеджер плагинов DLE (`install.xml` инъецирует SDK в `engine/init.php` и admin init).
3. В `api/`: `composer install`.
4. Админка **DLE API** — ключ + OAuth-клиент.

## Редирект `/api` → `/api/v2`

**Apache** (`api/.htaccess`): `/api` и `/api/v1` → `/api/v2` (308).

**nginx** (фрагмент `server`):

```nginx
location = /api {
    return 308 /api/v2/;
}
location /api/v1 {
    rewrite ^/api/v1/?(.*)$ /api/v2/$1 permanent;
}
location /api/v2/ {
    try_files $uri /api/v2/index.php?$query_string;
}
```

## Auth (HTTP)

`Authorization: Bearer <access_token>`  
Токен: `POST /api/v2/oauth/token` (`grant_type=client_credentials` или `password` с username/password пользователя DLE).

## SDK (`DcApi`)
>>>>>>> Current commit: Начало обновления до api v2

<<<<<<< New base: Update README.md
### Bearer (`Authorization: Bearer <access_token>`)
||||||| Common ancestor
Для установки достаточно скачать [релиз](https://github.com/Gokujo/dle_api/releases/latest).
Документация к API находится на сервере [POSTMAN](https://documenter.getpostman.com/view/7856564/2s93CLsZ6p). На данный момент она не полная и пополняется медленно, но верно.
Релизы выше только для версий DLE 16 и выше.
=======
```php
// после enable плагина bootstrap уже подключён; или вручную:
require_once DLEPlugins::Check(ROOT_DIR . '/api/sdk/bootstrap.php');
>>>>>>> Current commit: Начало обновления до api v2

<<<<<<< New base: Update README.md
| Метод | Путь | Описание |
|-------|------|----------|
| GET | `/me` | Данные владельца токена |
| GET | `/oauth/userinfo` | OIDC userinfo |
| GET | `/post` | Список новостей |
| GET | `/post/{id}` | Одна новость |
| POST | `/post` | Создание новости |
| POST | `/user` | Создание пользователя |
| POST | `/usergroup` | Создание группы |
| POST | `/plugin` | Создание плагина |
| GET | `/conversations` | Список бесед |
| GET | `/table/{name}` | Список записей таблицы |
| GET | `/table/{name}/{id}` | Запись по id |
| POST | `/table/{name}` | Создание записи |
| PUT | `/table/{name}/{id}` | Обновление записи |
| DELETE | `/table/{name}/{id}` | Удаление записи |
| POST | `/upload` | Загрузка файла |
| GET | `/xfields/{scope}` | Список доп. полей |
| GET | `/xfields/{scope}/{name}` | Одно доп. поле |
| POST | `/xfields/{scope}` | Создание доп. поля |
| POST | `/xfields/{scope}/encode` | Кодирование значений |
| PUT | `/xfields/{scope}/{name}` | Замена доп. поля |
| PATCH | `/xfields/{scope}/{name}` | Частичное обновление |
| DELETE | `/xfields/{scope}/{name}` | Удаление доп. поля |
||||||| Common ancestor
Чтобы пополнить описания к полям - делаем форк репозитория и редактируем файл в папке apidata **DLE-API.postman_collection.json**. Изменяем и делаем пуш риквест.
=======
$post = DcApi::prepareNewPost()
    ->withTitle('Заголовок')
    ->withCategory([12, 15])
    ->create();
>>>>>>> Current commit: Начало обновления до api v2

<<<<<<< New base: Update README.md
## Установка
||||||| Common ancestor
=======
$post->id();
$post->schema();
>>>>>>> Current commit: Начало обновления до api v2

<<<<<<< New base: Update README.md
1. Установите DevCraft Admin.
2. Упакуйте `upload/` и установите через менеджер плагинов DLE (`install.xml` инъецирует SDK в `engine/init.php` и admin init).
3. В `api/`: `composer install`.
4. Админка **DLE API** — ключ + OAuth-клиент.
||||||| Common ancestor
# Инструкция
Скачайте релиз. У вас три варианта для установки:
1. **При помощи bat-Скрипта. Для пользователей Windows**
Для этого устанавливаем [7Zip](https://www.7-zip.org/download.html).
После установки запускаем скрипт install_archive.bat.
После завершения установки - загружаем install.zip в менеджер плагинов.
=======
DcApi::modifyXfield('post')->upsert('myfield', [
    'name' => 'myfield',
    'description' => 'Test',
    'type' => 'text',
])->save();
```
>>>>>>> Current commit: Начало обновления до api v2

<<<<<<< New base: Update README.md
## Редирект `/api` → `/api/v2`
||||||| Common ancestor
1. **Упаковать самому**
Любым архиватором запаковать всё содержимое в папке **upload**, причём так, чтобы в корне архива был файл **install.xml** и папка **engine**.
Затем устанавливаем архив через менеджер плагинов.
=======
Фабрики: `prepare`, `prepareNewPost|User|Plugin|Usergroup`, `startConversation`, `prepareStaticPage`, `prepareFile`, `modifyXfield`.
>>>>>>> Current commit: Начало обновления до api v2

<<<<<<< New base: Update README.md
**Apache** (`api/.htaccess`): `/api` и `/api/v1` → `/api/v2` (308).
||||||| Common ancestor
1. **Просто залить**
Залейте папку **engine** в корень сайта и установите плагин через менеджер плагинов.
=======
Низкоуровневый Fluent: `api/src/Fluent/bootstrap.php` → `prepare('post')->…->create()`.
>>>>>>> Current commit: Начало обновления до api v2

<<<<<<< New base: Update README.md
**nginx** (фрагмент `server`):
||||||| Common ancestor
=======
Схемы таблиц: `api/src/Schema/`. Xfields: `GET/POST /api/v2/xfields/{post|user}/…`.
>>>>>>> Current commit: Начало обновления до api v2

<<<<<<< New base: Update README.md
```nginx
location = /api {
    return 308 /api/v2/;
}
location /api/v1 {
    rewrite ^/api/v1/?(.*)$ /api/v2/$1 permanent;
}
location /api/v2/ {
    try_files $uri /api/v2/index.php?$query_string;
}
```
||||||| Common ancestor
## Обновление
Заменить все файлы из папки **upload**, кроме **install.xml**.
=======
## OpenAPI
>>>>>>> Current commit: Начало обновления до api v2

<<<<<<< New base: Update README.md
## Auth (HTTP)

`Authorization: Bearer <access_token>`
Токен: `POST /api/v2/oauth/token` (`credential_type=api_key` с API-ключом, `auth` с логином/паролем DLE, или `oauth_client` с client_credentials).

## SDK (`DcApi`)

```php
require_once DLEPlugins::Check(ROOT_DIR . '/api/sdk/bootstrap.php');

$post = DcApi::prepareNewPost()
    ->withTitle('Заголовок')
    ->withCategory([12, 15])
    ->create();

$post->id();
$post->schema();

DcApi::modifyXfield('post')->upsert('myfield', [
    'name' => 'myfield',
    'description' => 'Test',
    'type' => 'text',
])->save();
```

Фабрики: `prepare`, `prepareNewPost|User|Plugin|Usergroup`, `startConversation`, `prepareStaticPage`, `prepareFile`, `modifyXfield`.

## Тесты

```bash
cd upload/api
composer install
vendor/bin/phpunit
```

53 интеграционных теста для всех эндпоинтов (PHPUnit 12, Guzzle 8). Требуют работающий DLE-сервер.

## OpenAPI

```bash
cd upload/api && composer openapi
```

Генерирует `apidata/openapi.yaml` из PHP-атрибутов (`src/OpenApi`, `src/Schema`, `src/Xfield/Schema`).

CI: workflow `openapi.yml` запускается автоматически при изменениях PHP-исходников на `main` и открывает PR с обновлённым `apidata/openapi.yaml`. mhdocs скачивает актуальный spec перед каждой сборкой документации.

## CI / CD

| Workflow | Назначение |
|----------|------------|
| `composer-check.yml` | Валидация composer.json, установка, PHP lint на PR/push |
| `dependabot-auto-merge.yml` | Auto-merge patch/minor Dependabot PR |
| `composer-update.yml` | `composer update` раз в месяц / вручную / после merge Dependabot |
||||||| Common ancestor
=======
```bash
cd upload/api && php composer.phar openapi
```
>>>>>>> Current commit: Начало обновления до api v2

## Удаление

<<<<<<< New base: Update README.md
Плагин в менеджере; каталоги `api/`, `devcraft/src/modules/DleApi/`, `engine/inc/dleapi.php`.

## Лицензия

GNU Affero General Public License v3 or later — см. [LICENSE](LICENSE).
||||||| Common ancestor
Удаляем плагин из менеджера плагинов
=======
Плагин в менеджере; каталоги `api/`, `devcraft/src/modules/DleApi/`, `engine/inc/dleapi.php`.
>>>>>>> Current commit: Начало обновления до api v2
