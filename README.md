[![DLE-20.0](https://img.shields.io/badge/DLE-20.0-green.svg?style=flat-square)](https://dle-news.ru/)
[![PHP-8.3](https://img.shields.io/badge/PHP-8.3-red.svg?style=flat-square)](https://www.php.net/)
[![DevCraft-Admin](https://img.shields.io/badge/DevCraft%20Admin-%E2%89%A5200.4.1-blue.svg?style=flat-square)](https://readme.devcraft.club/dev/devcraft_admin/install/)
![Version](https://img.shields.io/badge/Version-200.1.1-orange.svg?style=flat-square)

# DLE API

 Неофициальное REST API для DataLife Engine **20.0**. HTTP-поверхность `/api/v2` (ключи, OAuth2 Bearer). In-process SDK (`DcApi`) поставляется с DevCraft Admin ≥ 200.4.1.

 ## Требования

 | Компонент | Версия |
 |-----------|--------|
 | DLE | 20.0 |
 | PHP | ≥ 8.3 |
 | DevCraft Admin Panel | ≥ 200.4.1 |

 ## Возможности

 - **OAuth 2.0** — Authorization Code + PKCE, Client Credentials, Refresh Token, Revoke, Discovery
 - **CRUD** — универсальный доступ к таблицам DLE через `/table/{name}`
 - **Xfields** — чтение и запись дополнительных полей (`news`, `user`, `static`)
 - **Загрузка файлов** — `POST /upload` с валидацией типа и размера
 - **Посты** — листинг и получение публикаций с фильтрацией
 - **OpenAPI** — спецификация генерируется из PHP-атрибутов (CI workflow)
 - **Интеграционные тесты** — PHPUnit + Guzzle, покрытие всех эндпоинтов

 In-process SDK (`DcApi`, Schema, Fluent) **не входит в этот пакет** — он в DevCraft Admin ≥ 200.4.1 (`devcraft/src/sdk/dle/`). HTTP `/api/v2` использует его как потребитель.

 ## Документация

 | Раздел | Ссылка |
 |--------|--------|
 | Установка | [readme.devcraft.club/…/install](https://readme.devcraft.club/dev/dle/dle_api/200.1.1/install) |
 | Начало работы | [readme.devcraft.club/…/getting_started](https://readme.devcraft.club/dev/dle/dle_api/200.1.1/getting_started) |
 | Миграция v1 → v2 | [readme.devcraft.club/…/guides/migrate-v1-v2](https://readme.devcraft.club/dev/dle/dle_api/200.1.1/guides/migrate-v1-v2) |
 | Справочник HTTP | [readme.devcraft.club/…/reference/http](https://readme.devcraft.club/dev/dle/dle_api/200.1.1/reference/http) |
 | SDK (`DcApi`, в Admin) | [readme.devcraft.club/…/reference/sdk](https://readme.devcraft.club/dev/dle/dle_api/200.1.1/reference/sdk) · [слои Admin](https://readme.devcraft.club/dev/dle/devcraft_admin/200.4.1/guides/data_layers) |
 | OpenAPI спецификация | [`apidata/openapi.yaml`](apidata/openapi.yaml) |

 ## Лицензия

 [MIT](LICENSE)