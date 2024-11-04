<p align="center"><a href="https://gen-api.ru" target="_blank"><img src="https://api.gen-api.ru/storage/logo.svg" width="200" alt="GenAPI Logo"></a></p>

<p align="center">
<a href="https://packagist.org/packages/generative/genapi-sdk-php"><img src="https://img.shields.io/packagist/php-v/generative/genapi-sdk-php" alt="PHP Version"></a>
<a href="https://packagist.org/packages/generative/genapi-sdk-php"><img src="https://img.shields.io/packagist/v/generative/genapi-sdk-php?label=stable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/generative/genapi-sdk-php"><img src="https://img.shields.io/packagist/l/generative/genapi-sdk-php" alt="License"></a>
</p>

## Требования

- PHP версии 8.1 или выше с установленным расширением `libcurl`.

## Установка

### Установка с использованием Composer

1. [Установите Composer](https://getcomposer.org/download/), если он еще не установлен.
2. Выполните команду для установки библиотеки:

    ```bash
    composer require generative/genapi-sdk-php
    ```

### Ручная установка через `composer.json`

1. Откройте файл `composer.json` вашего проекта и добавьте строку зависимости:

    ```json
    {
        "require": {
            "php": ">=8.1",
            "generative/genapi-sdk-php": "^1.0"
        }
    }
    ```

2. Обновите зависимости, перейдя в директорию с `composer.json` и выполнив команду:

    ```bash
    composer update
    ```

## Начало работы

1. **Импортируйте необходимые классы**:

    ```php
    use GenAPI\Client;
    ```

2. **Создайте экземпляр клиента**:

   Создайте объект класса `Client` и задайте API ключ. Его можно получить в личном кабинете GenAPI.

    ```php
    $client = new Client();
    $client->setAuthToken('yourBearerToken');
    ```

## Основные возможности

С помощью этой библиотеки вы можете:

- Создавать задачи к нейросетям
- Создавать задачи к ИИ функциям
- Проверять статус задач
- Получать информацию о своем аккаунте

## Расширенные возможности

Библиотека спроектирована так, чтобы вы могли легко адаптировать её под свои нужды. Вы всегда можете расширить класс
`Client`, добавив собственную логику, обработчики или дополнительные функции.
