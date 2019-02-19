# yandex-market-reviews
Замена публичному Yandex.API. Получение ТОП10 отзывов и информации о магазине.

## Установка
`composer require s25/yandex-market-reviews`

## Пример использования
`php test/test.php`
```PHP
<?php

require __DIR__.'/../vendor/autoload.php';

use S25\Scrapping\YandexReviews;
use S25\HTTPClient\Client;
use S25\HTTPClient\UserAgent;

$client   = new Client();
$client->setUserAgent(UserAgent::getRandom());

// Скачиваем страницу с отзывами
$result = $client->get('https://market.yandex.ru/shop--ozon-ru/443605/reviews');

$yandexReviews  = new YandexReviews($result);

// 10 последних отзывов со страницы магазина
$reviews = $yandexReviews->getLastTenReviews();

// Кол-во оценок для 5,4,3,2,1 звёзд
$stars   = $yandexReviews->getStars();

// Прочая информация со страницы отзывов
$summary = $yandexReviews->getSummaryRating();

print_r($reviews[5]);
print_r($stars);
print_r($summary);
```

### 10 последних отзывов со страницы магазина

```PHP
$reviews = $yandexReviews->getLastTenReviews();
print_r($reviews);

Array
(
    [id] => 87411609
    [date] => 2019-02-18
    [author] => dizstancia d.
    [ratingValue] => 4
    [img] => //avatars.mds.yandex.net/get-yapic/24700/26258836-1152698/islands-retina-middle
    [delivery] => самовывоз
    [city] => Ростов-на-Дону
    [review] => Array
        (
            [positive] => Цена и оплата спасибо от сбербанк.
            [negative] => Дорогая доставка до пункта выдачи.
            [comment] => Хочется бесплатной доставки до пункта самовывоза.
        )

)
more 9...
}
```

### Кол-во оценок для 5,4,3,2,1 звёзд

```PHP
$stars   = $yandexReviews->getStars();
print_r($stars);

Array
(
    [5] => Array
        (
            [value] => 50
            [count] => 6293
        )

    [4] => Array
        (
            [value] => 12
            [count] => 1511
        )

    [3] => Array
        (
            [value] => 12
            [count] => 1536
        )

    [2] => Array
        (
            [value] => 6
            [count] => 778
        )

    [1] => Array
        (
            [value] => 19
            [count] => 2375
        )

)
```

### Прочая информация со страницы отзывов
```PHP
$summary = $yandexReviews->getSummaryRating();
print_r($summary);

Array
(
    [communication]              => 4,4    // Общение
    [description_quality]        => 4,6    // Соответствие товара описанию
    [handling_speed]             => 4,6    // Скорость обработки заказа
    [shipping_speed_and_quality] => 4,2    // Скорость и качество доставки
    [easy_pickup]                => 4,6    // Удобство самовывоза
    [rating_3month]              => 4,3    // Рейтинг за 3 месяца
    [review_3month]              => 4039   // Отзывов за 3 месяца
    [review_all]                 => 63962  // Всего отзывов
    [happy_buyers]               => 87     // Процент покупателей купили бы тут снова
)
```
