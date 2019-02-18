<?php

require 'vendor/autoload.php';

use S25\Scrapping\Reviews;

// Скачиваем страницу с отзывами
$html   = new HTTPClient();
$result = $html->get('https://market.yandex.ru/shop--ozon-ru/443605/reviews');

$review  = new Reviews($result);

// 10 последних отзывов со страницы магазина
$reviews = $review->getLastTenReviews();

// Кол-во оценок для 5,4,3,2,1 звёзд
$stars   = $review->getStars();

// Прочая информация со страницы отзывов
$summary = $review->getSummaryRating();
