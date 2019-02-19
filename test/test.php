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