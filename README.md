[![DLE-20.0](https://img.shields.io/badge/DLE-20.0-green.svg?style=flat-square)](https://dle-news.ru/)
[![PHP-8.3](https://img.shields.io/badge/PHP-8.3-red.svg?style=flat-square)](https://www.php.net/)
[![DevCraft-Admin](https://img.shields.io/badge/DevCraft%20Admin-%E2%89%A5200.4.0-blue.svg?style=flat-square)](https://readme.devcraft.club/dev/devcraft_admin/install/)
![Version](https://img.shields.io/badge/Version-200.1.0-orange.svg?style=flat-square)

# DLE API

Неофициальное REST API для DataLife Engine **20.0**.

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

```php
// после enable плагина bootstrap уже подключён; или вручную:
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

Низкоуровневый Fluent: `api/src/Fluent/bootstrap.php` → `prepare('post')->…->create()`.

Схемы таблиц: `api/src/Schema/`. Xfields: `GET/POST /api/v2/xfields/{post|user}/…`.

## OpenAPI

```bash
cd upload/api && php composer.phar openapi
```

## Удаление

Плагин в менеджере; каталоги `api/`, `devcraft/src/modules/DleApi/`, `engine/inc/dleapi.php`.
