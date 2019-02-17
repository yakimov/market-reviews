<?php

namespace S25\Scrapping;
use Symfony\Component\DomCrawler\Crawler;

class Reviews extends BaseScrapping
{
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
                $value = preg_replace('@[^\d]*@i', '', $this->filterText('div.rating-review__value', $dom));
                $count = preg_replace('@[^\d]*@i', '', $this->filterText('div.rating-review__count', $dom));
                $result[$rate] = ['value' => $value, 'count' => $count];
            }
        );

        return $result;
    }



}