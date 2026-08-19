[![DLE-20.0](https://img.shields.io/badge/DLE-20.0-green.svg?style=flat-square)](https://dle-news.ru/)
[![PHP-8.3](https://img.shields.io/badge/PHP-8.3-red.svg?style=flat-square)](https://www.php.net/)
[![DevCraft-Admin](https://img.shields.io/badge/DevCraft%20Admin-%E2%89%A5200.4.0-blue.svg?style=flat-square)](https://readme.devcraft.club/dev/devcraft_admin/install/)
![Version](https://img.shields.io/badge/Version-200.1.0-orange.svg?style=flat-square)

# DLE API

Неофициальное REST API для DataLife Engine **20.0**.

HTTP-поверхность: **`/api/v2`** — Slim 4, OAuth2 Authorization Server (league/oauth2-server 9), Fluent Schema ORM, OpenAPI.

In-process SDK: глобальный **`DcApi`**.

- Документация: [readme.devcraft.club/dev/dle_api/](https://readme.devcraft.club/dev/dle_api/install/)
- OpenAPI: [`apidata/openapi.yaml`](apidata/openapi.yaml)

## Требования

| Компонент      | Версия    |
| -------------- | --------- |
| DLE            | ≥ 20.0    |
| PHP            | ≥ 8.3     |
| DevCraft Admin | ≥ 200.4.0 |

## Эндпоинты `/api/v2`

### Публичные (без авторизации)

| Метод     | Путь                                      | Описание                    |
| --------- | ----------------------------------------- | --------------------------- |
| GET       | `/health`                                 | Версия, статус              |
| GET       | `/.well-known/oauth-authorization-server` | OAuth2 AS discovery         |
| GET, POST | `/oauth/authorize`                        | Авторизация (redirect flow) |
| POST      | `/oauth/token`                            | Выдача access_token         |
| POST      | `/oauth/revoke`                           | Отзыв токена                |

### Raw API Key (`Authorization: Bearer <apiKey>`)

| Метод | Путь         | Описание                         |
| ----- | ------------ | -------------------------------- |
| GET   | `/key/check` | Проверка ключа, scopes, identity |
