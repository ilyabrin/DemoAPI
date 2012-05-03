DemoAPI
=======

Пример проекта для ООО КБ Смоленский Банк

# Описание
Это пример реализации API для своего веб-приложения. API осуществляет проверку входящих запросов и, если данные верны,  выдает ответ в формате JSON. 
Пример предназначен для обмена данными с API используя внешний сервер, но есть и клиентский пример.

### Установка
Для запуска примера и его настройки нужно сделать следующее:
 
1. Скачать и распаковать архив с кодом и примерами. 
2. Загрузить содержимое в любую удобную директорию на сервере. 
3. Изменить параметры доступа к БД в файле <code>config.php</code>
4. Для ручной установки тестовой sql-таблицы используйте <code>dump.api.sql</code>.
5. Перед запуском примера <code>example.php</code> нужно указать полный URL до <code>api.php</code> в переменной $API_URL
6. Запустить браузер и обратиться по адресу: <code>http://your_url_here/example.php</code>

Если все сделано правильно, на экране появится json-ответ из API.

Дополнительно я подготовил и другие примеры: <code>example2.php</code>, <code>ajax.html</code>

## Структура проекта
После распаковки архива структура файлов и директорий следующая:

* <strong>api.php</strong> - ядро API. Здесь происходит проверка входящих данных и отсюда же уходит ответ, если все условия TRUE
* <strong>api.utils.php</strong> - вспомогательные функции для проверки данных
* <strong>config.php</strong> - конфиг базы данных
* <strong>demo.data.php</strong> - подготовленные ответы для API, которые выдаются по запросу в зависимости от входящих параметров
* <strong>example.php</strong> - пример посылает подписанный запрос API с методом getUserInfo (есть еще <code>getUserOnline</code> и <code>getServerTime</code>)
* <strong>example2.php</strong> - пример того, как можно визуализировать json-данные в своем шаблоне. 
* <strong>ajax.html</strong> - вывод данных полученных от API в обычном <code>alert();</code>
* <strong>pdo/</strong> - здесь лежит класс для работы с БД посредством PDO
* <strong>pdo/class.db.php</strong> - вот, кстати, и сам класс
* <strong>pdo/error.css</strong> - таблица стилей для вывода ошибок
* <strong>themengn/</strong> - директория шаблонизатора
* <strong>themengn/templates.class.php</strong> - класс шаблонизатора
* <strong>demo/</strong> - в этой директории расположен пример шаблона, который используется в <code>example2.php</code>
* <strong>demo/images</strong> - картинки
* <strong>demo/styles.css</strong> - стили
* <strong>demo/userinfo.html</strong> - шаблон


## Дополнения
Вот так все работает на моем сервере: 

Серверные запросы:
* http://smolget.net/sb/example.php
* http://smolget.net/sb/example2.php

Клиентский запрос:
* http://smolget.net/sb/ajax.html

Прямой GET запрос:
* http://smolget.net/sb/api.php?app_id=110&method=getUserInfo&token=f4a7784848652029e4bbf2e0b5363c6f