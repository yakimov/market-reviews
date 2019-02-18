<?php

namespace S25\Scrapping;
use Symfony\Component\DomCrawler\Crawler;

class BaseScrapping
{
    protected $dom;

    public function __construct($html)
    {
        $this->dom = new Crawler();
        $this->dom->addHtmlContent($html);
    }

    protected function filterAttr($query, $type='src', Crawler $dom = null)
    {
        return $this->filter($query, $dom)->count() > 0 ? $this->filter($query, $dom)->attr($type) : false;
    }

    protected function filterHtml($query, Crawler $dom = null)
    {
        return $this->filter($query, $dom)->count() > 0 ? $this->filter($query, $dom)->html() : false;
    }

    protected function filterText($query, Crawler $dom = null)
    {
        return $this->filter($query, $dom)->count() > 0 ? $this->filter($query, $dom)->text() : false;
    }

    protected function filter($query, Crawler $dom = null)
    {
        if(!$dom)
        {
            $dom = $this->dom;
        }
        return $dom->filter($query);
    }
}