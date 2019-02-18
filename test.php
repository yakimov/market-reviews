<?php

require 'vendor/autoload.php';

use S25\Scrapping\YandexReviews;

// Скачиваем страницу с отзывами
$html   = new HTTPClient();
$result = $html->get('https://market.yandex.ru/shop--ozon-ru/443605/reviews');

$yandexReviews  = new YandexReviews($result);

// 10 последних отзывов со страницы магазина
$reviews = $yandexReviews->getLastTenReviews();

// Кол-во оценок для 5,4,3,2,1 звёзд
$stars   = $yandexReviews->getStars();

// Прочая информация со страницы отзывов
$summary = $yandexReviews->getSummaryRating();
