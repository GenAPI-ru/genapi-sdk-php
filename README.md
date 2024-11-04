<p align="center"><a href="https://gen-api.ru" target="_blank"><img src="https://api.gen-api.ru/storage/logo.svg" width="200" alt="GenAPI Logo"></a></p>

<p align="center">
<a href="https://packagist.org/packages/generative/genapi-sdk-php"><img src="https://img.shields.io/packagist/php-v/generative/genapi-sdk-php" alt="PHP Version"></a>
<a href="https://packagist.org/packages/generative/genapi-sdk-php"><img src="https://img.shields.io/packagist/v/generative/genapi-sdk-php?label=stable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/generative/genapi-sdk-php"><img src="https://img.shields.io/packagist/l/generative/genapi-sdk-php" alt="License"></a>
</p>

# О нас

**GenAPI** — платформа, предлагающая API для интеграции различных нейросетей в ваши проекты. Она облегчает использование современных моделей AI для обработки текста, генерации изображений, аудио и видео. Регулярно обновляемый список нейросетей предоставляет доступ к последним технологиям в области искусственного интеллекта. Узнайте больше на сайте [GenAPI](https://gen-api.ru).

## 🚀 Требования

- PHP версии 8.1 или выше с установленным расширением `libcurl`.

## 📦 Установка

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

## 🛠️ Начало работы

1. **Импортируйте необходимые классы**:

    ```php
    use GenAPI\Client;
    ```

2. **Создайте экземпляр клиента**:

   Создайте объект класса `Client` и задайте API ключ, который можно получить в [личном кабинете](https://gen-api.ru/account/api-tokens).

    ```php
    $client = new Client();
    $client->setAuthToken('yourBearerToken');
    ```

## 🌟 Основные возможности

С помощью этой библиотеки вы можете:

- создавать задачи для нейросетей
- работать с ИИ функциями
- проверять статус задач
- получать информацию о своем аккаунте и балансе

## 🔧 Расширенные возможности

Библиотека спроектирована для легкой адаптации под ваши нужды. Вы можете расширить класс `Client`, добавив собственную логику, обработчики или функции.

## 🌐 Доступные нейросети

Список регулярно обновляется, следите за последними изменениями на сайте [GenAPI](https://gen-api.ru/models).

| Название нейросети    | Версия                                |
|-----------------------|---------------------------------------|
| 🤖 ChatGPT            | 3.5, 4, omni, o1                      |
| 🌞 Claude             | Haiku 3, Sonnet 3, Sonnet 3.5, Opus 3 |
| 🌈 Stable Diffusion   | XL, lightning, 3, 3.5, ControlNet     |
| 🗣️ TTS от OpenAI     | tts, tts-hd                           |
| 🎨 DALL-E             | 2, 3                                  |
| 🌌 Midjourney         | 5.0, 5.1, 5.2, 6.0, 6.1               |
| 🔍 Real ESRGAN        |                                       |
| 📝 Whisper            |                                       |
| 📊 Embeddings         | 3-small, 3-large, ada-002             |
| 📷 Midas              |                                       |
| 🌟 Luma               |                                       |
| 🎉 Fooocus            |                                       |
| 🎵 Suno               |                                       |
| ⚡ Flux                | dev, schnell, pro, realism, pro v1.1  |
| 🎥 CogVideoX 5B       |                                       |
| 🚀 Runway Gen-3 Alpha | gen3a_turbo                           |
| 🌟 Kling              | pro, standard                         |
| 🎨 Kolors             |                                       |
