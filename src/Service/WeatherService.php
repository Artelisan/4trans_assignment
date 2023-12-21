<?php

namespace App\Service;

use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{

    public function __construct(
        private readonly string              $weatherbitApiKey,
        private readonly string              $weatherbitApiUrl,
        private readonly HttpClientInterface $client,
    )
    {
    }

    /** @throws ExceptionInterface */
    public function updateWeatherForCityByName(string $name): float
    {
        try {
            $response = $this->client->request(
                Request::METHOD_GET,
                "{$this->weatherbitApiUrl}?city={$name}&key={$this->weatherbitApiKey}"
            );
        } catch (TransportExceptionInterface $e) {
            throw new TransportException("Failed to get temperature for city: $name", $e->getCode());
        }

        return $response->toArray()['data'][0]['temp'];
    }
}
