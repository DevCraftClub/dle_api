[![GitHub issues](https://img.shields.io/github/issues/Gokujo/dle_api.svg?style=flat-square)](https://github.com/Gokujo/dle_api/issues)
[![GitHub forks](https://img.shields.io/github/forks/Gokujo/dle_api.svg?style=flat-square)](https://github.com/Gokujo/dle_api/network)
[![GitHub license](https://img.shields.io/github/license/Gokujo/dle_api.svg?style=flat-square)](https://github.com/Gokujo/dle_api/blob/master/LICENSE)

![DLE-16.x](https://img.shields.io/badge/DLE-16.x-green.svg?style=flat-square)
![MySQL-5.5.6](https://img.shields.io/badge/MySQL-5.5.6-red.svg?style=flat-square)

![Версия_релиза](https://img.shields.io/github/manifest-json/v/Gokujo/dle_api?filename=manifest.json&style=flat-square)
![Версия_релиза](https://img.shields.io/badge/Version-BETA-orange.svg?style=flat-square)

# DLE API
Модификация для админпанели и глобальные функции для моих разработок
Совместимость проверенна на DLE-версиях 16.х. Для корректной работы требуется минимальная версия MySQL 5.5.6 или MariaDB 10.0, поскольку используются Foreign Key, которые требуют наличие InnoDB.

Для установки достаточно скачать [релиз](https://github.com/Gokujo/dle_api/releases/latest).
Документация к API находится на сервере [POSTMAN](https://documenter.getpostman.com/view/7856564/2s93CLsZ6p). На данный момент она не полная и пополняется медленно, но верно.
Релизы выше только для версий DLE 16 и выше.

Чтобы пополнить описания к полям - делаем форк репозитория и редактируем файл в папке apidata **DLE-API.postman_collection.json**. Изменяем и делаем пуш риквест.


# Инструкция
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


## Обновление
Заменить все файлы из папки **upload**, кроме **install.xml**.


## Удаление
Удаляем **из корня** сайта папку **api**, a так-же из папки **engine/inc** файл **dleapi.php** и из **engine/skins/images** файл **dleapi.png**.

Удаляем плагин из менеджера плагинов
