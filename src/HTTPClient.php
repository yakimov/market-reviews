<?php

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class HTTPClient
{
    private $client;
    private $proxy      = '';
    private $method;
    private $headers    = [];
    private $query      = [];
    private $formParams = [];

    public function __construct()
    {
        //ProxyPoolRedis::getPort();
        $this->setMethod();
        $this->setHeaders([
            'accept'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'accept-encoding'           => 'gzip, deflate, br',
            'accept-language'           => 'en,en-US;q=0.9,en;q=0.8,ja;q=0.7,fr;q=0.6,es;q=0.5,de;q=0.4',
            'cache-control'             => 'no-cache',
            'pragma'                    => 'no-cache',
            'upgrade-insecure-requests' => '1',
        ]);
        $this->client  = new Client(['cookies' => new CookieJar(), 'headers' => $this->headers]);
    }

    public function setProxy($host, $port)
    {
        $this->proxy = sprintf('%s:%s', $host, $port);
    }

    public function setHeaders(array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    public function setMethod($method = 'GET')
    {
        $this->method = $method;
    }

    public function setQuery(array $query = [])
    {
        $this->query = $query;
    }

    public function setUserAgent($userAgent)
    {
        $this->headers['user-agent'] = $userAgent;
    }

    public function setReferer($referer)
    {
        $this->headers['referer'] = $referer;
    }

    public function setFormParams(array $formParams = [])
    {
        $this->formParams = $formParams;
    }

    public function get($url)
    {
        $responce = $this->client->request($this->method, $url,
            [
                'headers'     => $this->headers,
                'form_params' => $this->formParams,
                'proxy'       => $this->proxy,
            ]);
        return (string)$responce->getBody();
    }

}