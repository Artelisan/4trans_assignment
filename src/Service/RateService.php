<?php

namespace App\Service;

use DateTime;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RateService
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly XmlEncoder $xmlEncoder,
        private readonly FilesystemAdapter $cache,
    )
    {
    }

    /** @throws ExceptionInterface */
    public function getRate(string $currency, DateTime $date)
    {
        $cacheKey = md5($currency . $date->format('Y-m-d'));
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $response = $this->httpClient->request(
            Request::METHOD_GET,
            "https://nbs.sk/export/sk/exchange-rate/{$date->format('Y-m-d')}/xml"
        )->getContent();

        $dataArray = $this->xmlEncoder->decode($response, 'xml');

        foreach ($dataArray['Cube']['Cube']['Cube'] as $line) {
            if ($line['@currency'] === $currency) {
                $rate = $line['@rate'];
                $cacheItem->set($rate);
                $this->cache->save($cacheItem);

                return $rate;
            }
        }

        return null;
    }
}
