[![DLE-20.0](https://img.shields.io/badge/DLE-20.0-green.svg?style=flat-square)](https://dle-news.ru/)
[![PHP-8.3](https://img.shields.io/badge/PHP-8.3-red.svg?style=flat-square)](https://www.php.net/)
[![DevCraft-Admin](https://img.shields.io/badge/DevCraft%20Admin-%E2%89%A5200.4.0-blue.svg?style=flat-square)](https://readme.devcraft.club/dev/devcraft_admin/install/)
![Version](https://img.shields.io/badge/Version-200.1.0-orange.svg?style=flat-square)

# DLE API

 Неофициальное REST API для DataLife Engine **20.0**. Предоставляет HTTP-поверхность (`/api/v2`) и in-process SDK (`DcApi`) для интеграции с внешними сервисами и фронтенд-приложениями.

 ## Требования

 | Компонент | Версия |
 |-----------|--------|
 | DLE | 20.0 |
 | PHP | ≥ 8.3 |
 | DevCraft Admin Panel | ≥ 200.4.0 |

 ## Возможности

 - **OAuth 2.0** — Authorization Code + PKCE, Client Credentials, Refresh Token, Revoke, Discovery
 - **CRUD** — универсальный доступ к таблицам DLE через `/table/{name}`
 - **Xfields** — чтение и запись дополнительных полей (`news`, `user`, `static`)
 - **Загрузка файлов** — `POST /upload` с валидацией типа и размера
 - **Посты** — листинг и получение публикаций с фильтрацией
 - **In-process SDK** — глобальный `DcApi` для использования внутри DLE-модулей
 - **OpenAPI** — спецификация генерируется из PHP-атрибутов (CI workflow)
 - **Интеграционные тесты** — PHPUnit + Guzzle, покрытие всех эндпоинтов

 ## Документация

 | Раздел | Ссылка |
 |--------|--------|
 | Установка | [readme.devcraft.club/…/install](https://readme.devcraft.club/dev/dle/dle_api/200.1.0/install) |
 | Начало работы | [readme.devcraft.club/…/getting_started](https://readme.devcraft.club/dev/dle/dle_api/200.1.0/getting_started) |
 | Миграция v1 → v2 | [readme.devcraft.club/…/guides/migrate-v1-v2](https://readme.devcraft.club/dev/dle/dle_api/200.1.0/guides/migrate-v1-v2) |
 | Справочник HTTP | [readme.devcraft.club/…/reference/http](https://readme.devcraft.club/dev/dle/dle_api/200.1.0/reference/http) |
 | SDK | [readme.devcraft.club/…/reference/sdk](https://readme.devcraft.club/dev/dle/dle_api/200.1.0/reference/sdk) |
 | OpenAPI спецификация | [`apidata/openapi.yaml`](apidata/openapi.yaml) |

 ## Лицензия

 [AGPL-3.0-or-later](LICENSE)