[![GitHub issues](https://img.shields.io/github/issues/Gokujo/dle_api.svg?style=flat-square)](https://github.com/Gokujo/dle_api/issues)
[![GitHub forks](https://img.shields.io/github/forks/Gokujo/dle_api.svg?style=flat-square)](https://github.com/Gokujo/dle_api/network)
[![GitHub license](https://img.shields.io/github/license/Gokujo/dle_api.svg?style=flat-square)](https://github.com/Gokujo/dle_api/blob/master/LICENSE)

![DLE-13.x](https://img.shields.io/badge/DLE-13.x-green.svg?style=flat-square)
![MySQL-5.5.6](https://img.shields.io/badge/MySQL-5.5.6-red.svg?style=flat-square)

![Версия_релиза](https://img.shields.io/badge/Version-0.1.3-orange.svg?style=flat-square)
![Версия_релиза](https://img.shields.io/badge/Version-BETA-orange.svg?style=flat-square)

# DLE API
Модификация для админпанели и глобальные функции для моих разработок
Совместимость проверенна на DLE-версиях 13.х. Для корректной работы требуется минимальная версия MySQL 5.5.6 или MariaDB 10.0, поскольку используются Foreign Key, которые требуют наличие InnoDB.

Для установки достаточно скачать [релиз](https://github.com/Gokujo/dle_api/releases/latest).
Документация к API находится на сервере [POSTMAN](https://documenter.getpostman.com/view/7856564/SW7T9BsW). На данный момент она не полная и пополняется медлено, но верно.
Релизы выше только для версий DLE 13 и выше.

Для пожеланий можно использовать [feathub](https://feathub.com/Gokujo/dle_api).
[![Feature Requests](https://feathub.com/Gokujo/dle_api?format=svg)](https://feathub.com/Gokujo/dle_api)


# DLE >= 13.x
Скачайте релиз. У вас три варианта для установки:
1. **При помощи bat-Скрипта. Для пользователей Windows**
Для этого устанавливаем [7Zip](https://www.7-zip.org/download.html).
После установки запускаем скрипт install_archive.bat.
После завершения установки - загружаем install.zip в менеджер плагинов.

1. **Упаковать самому**
Любым архиватором запаковать всё содержимое в папке **upload**, причём так, чтобы в корне архива был файл **install.xml** и папка **engine**.
Затем устанавливаем архив через менеджер плагинов.

1. **Просто залить**
Залейте папку **engine** в корень сайта и установите плагин через менеджер плагинов.



# DLE < 13
В теории, и на движках младше всё должно работать, поскольку в процессе не затрагиваются файлы движка. Но, это не точно, я не пробовал, не эксперементировал. На свой страх и риск.

## Установка
Залить папки **api** и **engine** из папки **upload** в корень сайта. Затем выполнить запрос в базу данных:

```SQL
CREATE TABLE {prefix}_api_keys (
	id int auto_increment
		primary key,
	api varchar(255) not null,
	is_admin tinyint(1) default 0 not null,
	creator int default 0 null,
	created datetime default CURRENT_TIMESTAMP not null,
	active tinyint(1) default 0 not null,
	user_id int default 0 not null,
	constraint {prefix}_api_keys_key_uindex
		unique (api)
);

create table {prefix}_api_scope (
	scope_id int auto_increment
		primary key,
	`table` varchar(255) null,
	`read` tinyint(1) default 0 not null,
	`write` tinyint(1) default 0 not null,
	`delete` tinyint(1) default 0 not null,
	key_id int default 0 not null,
	constraint {prefix}_api_scope_{prefix}_api_keys_id_fk
		foreign key (key_id) references {prefix}_api_keys (id)
			on update cascade on delete cascade
);

INSERT INTO {prefix}_admin_sections (name, title, descr, icon, allow_groups) VALUES ('dleapi', 'DLE-API', 'Неофициальное API для DLE. Раздел по созданию и управлению над ключами доступа к API.', '/engine/skins/images/icons/dleapi.png', 1);
```


## Удаление
Удаляем **из корня** сайта папку **api**, a так-же из папки **engine/inc** файл **dleapi.php** и из **engine/skins/images** файл **dleapi.png**.

Выполняем запрос в базу данных:


```SQL
DELETE FROM {prefix}_admin_sections WHERE `name` = 'dleapi';
DROP TABLE {prefix}_api_keys cascade, {prefix}_api_scope cascade;
```

**{prefix}** заменяем на свой префикс базы данных.
