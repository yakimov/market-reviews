<?php

namespace S25\Scrapping;
use Symfony\Component\DomCrawler\Crawler;

class YandexReviews extends BaseScrapping
{
    public function __construct($html)
    {
        parent::__construct($this->fixYandexHtml($html));
    }

    public function getLastTenReviews()
    {
        return $this->dom->filter('div.n-product-review-item')->each(
            function (Crawler $dom) {
                $result = [];
                $result['id']          = $dom->attr('data-review-id');
                $result['date']        = $dom->filterXpath("//meta[@itemprop='datePublished']")->attr('content');
                $result['‌‌author']      = $dom->filterXpath("//meta[@itemprop='author']")->attr('content');
                $result['ratingValue'] = $dom->filterXpath("//meta[@itemprop='ratingValue']")->attr('content');
                $result['img']         = $this->getImg($dom);
                $result['delivery']    = $this->getDelivery($dom);
                $result['city']        = $this->getCity($dom);
                $result['review']      = $this->getReview($dom);

                return $result;
            });
    }

    private function getImg(Crawler $dom)
    {
        return $this->filterAttr('img.n-product-review-user__avatar', 'src', $dom);
    }

    private function getDelivery(Crawler $dom)
    {
        return preg_replace('@^Способ покупки: @i', '', $this->filterText('span.n-product-review-item__delivery', $dom));
    }

    private function getCity(Crawler $dom)
    {
        return preg_replace('@^[^,]+, @i', '',  $this->filterText('span.n-product-review-item__date-region', $dom));
    }

    private function getReview($dom)
    {
        $result = [];
        $dom->filter('dl.n-product-review-item__stat')->each(
            function (Crawler $dom) use(&$result) {
                $name  = $this->filterText('dt', $dom);
                $value = $this->filterText('dd', $dom);

                switch ($name)
                {
                    case 'Достоинства: ':
                        $result['positive'] = $value;
                        break;
                    case 'Недостатки: ':
                        $result['negative'] = $value;
                        break;
                    case 'Комментарий: ':
                        $result['comment'] = $value;
                        break;
                }
            }
        );

        return $result;
    }

    public function getStars()
    {
        $result = [];
        $this->dom->filter('div.product-rating-stat a.rating-review')->each(
            function (Crawler $dom) use(&$result)
            {
                $rate  = $this->filterAttr('div.n-rating-stars', 'data-rate', $dom);
                $value = $this->clearDigits($this->filterText('div.rating-review__value', $dom));
                $count = $this->clearDigits($this->filterText('div.rating-review__count', $dom));
                $result[$rate] = ['value' => $value, 'count' => $count];
            }
        );

        return $result;
    }

    public function getSummaryRating()
    {
        $result = [];
        $this->dom->filter('div.n-review-factors-summary-rating__header')->each(
            function (Crawler $dom) use (&$result) {
                $name  = $this->filterText('div.n-review-factors-summary-rating__description', $dom);
                $value = $this->filterText('div.n-review-factors-summary-rating__value', $dom);
                switch ($name)
                {
                    case 'Общение':
                        $result['communication'] = $value;
                        break;
                    case 'Соответствие товара описанию':
                        $result['description_quality'] = $value;
                        break;
                    case 'Удобство самовывоза':
                        $result['easy_pickup'] = $value;
                        break;
                    case 'Скорость обработки заказа':
                        $result['handling_speed'] = $value;
                        break;
                    case 'Скорость и качество доставки':
                        $result['shipping_speed_and_quality'] = $value;
                        break;
                }
            }
        );
        $result['rating_3month'] = $this->clearDigits($this->dom->filter('span.n-reviews-shop-rating-summary__rating-count-real')->eq(0)->text());
        $result['review_3month'] = $this->clearDigits($this->dom->filter('span.n-reviews-shop-rating-summary__rating-count-real')->eq(1)->text());
        $result['review_all']    = $this->clearDigits($this->dom->filter('span.n-reviews-shop-rating-summary__rating-count-real')->eq(2)->text());
        $result['happy_buyers']  = $this->clearDigits($this->dom->filter('div.n-review-factors-summary__recommend')->text());

        return $result;
    }

    /*
     * Фикс для Яндекса который не умеет экранировать <
     *
     * @param string $html - html в котором нужно исправить проблему
     * @return string — исправленный html
     */
    private function fixYandexHtml($html)
    {
        return str_replace('< 1%', '&lt;&nbsp;1%', $html);
    }

    private function clearDigits($digits)
    {
        return preg_replace('@[^\d,]*@i', '', $digits);
    }



}