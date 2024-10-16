<?php

namespace Egio\Ps_Weather_Forecast\Service;

use GuzzleHttp\Client;

class WeatherApiService
{
    private $client;
    private $apiKey;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://api.meteo-concept.com/api/']);
        $this->apiKey = \Configuration::get('PS_WEATHER_API_KEY');
    }

    public function getCurrentWeather($city)
    {
        return $this->makeRequest('forecast/daily/0', $city);
    }

    public function getForecastByDay($city)
    {
        return $this->makeRequest('forecast/daily', $city);
    }

    public function getForecastByQuarterDay($city)
    {
        return $this->makeRequest('forecast/nextHours', $city);
    }

    public function getHourlyForecast($city)
    {
        return $this->makeRequest('forecast/hourly', $city);
    }

    private function makeRequest($endpoint, $city)
    {
        try {
            $inseeCode = $this->getInseeCode($city);
            if (!$inseeCode) {
                return ['error' => "City not found."];
            }

            $response = $this->client->request('GET', $endpoint, [
                'query' => [
                    'token' => $this->apiKey,
                    'insee' => $inseeCode
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            $errorMessage = "Internal server error, please try later";

            switch ($e->getCode()) { // those messages must be non technical
                case 400:
                    $errorMessage = "City not found, please check your parameters.";
                    break;
                case 401:
                    $errorMessage = "Authentication failed, please check your API key.";
                    break;
                case 403:
                    $errorMessage = "Authentication failed, please check your API key.";
                    break;
                case 404:
                    $errorMessage = "Page not found, please contact us.";
                    break;
                case 500:
                    $errorMessage = "Internal server error, please contact us.";
                    break;
                case 503:
                    $errorMessage = "API is temporarily unavailable, please try again later.";
                    break;
            }

            return ['error' => $errorMessage];
        }
    }

    private function getInseeCode($city)
    {
        $response = $this->client->request('GET', 'location/cities', [
            'query' => [
                'token' => $this->apiKey,
                'search' => $city
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        if (!empty($data['cities']) && isset($data['cities'][0]['insee'])) {
            return $data['cities'][0]['insee'];
        }

        return null;
    }    
}

