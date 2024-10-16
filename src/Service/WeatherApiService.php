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
        $inseeCode = $this->getInseeCode($city);
        if (!$inseeCode) {
            return ['error' => "City not found."];
        }
    
        try {
            $response = $this->client->request('GET', $endpoint, [
                'query' => [
                    'token' => $this->apiKey,
                    'insee' => $inseeCode
                ]
            ]);
    
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            $errorMessage = "";

            switch ($e->getCode()) {
                case 400:
                    $errorMessage = "Internal server error, please try later"; // trying to remove technical words from the reposnese message
                    break;
                case 401:
                    $errorMessage = "Internal server error, please try later";
                    break;
                case 403:
                    $errorMessage = "Internal server error, please try later";
                    break;
                case 404:
                    $errorMessage = "Internal server error, please try later";
                    break;
                case 500:
                    $errorMessage = "An error occurred, please try again later";
                    break;
                case 503:
                    $errorMessage = "The API is currently unavailable, please try again in a few minutes";
                    break;
                default:
                    $errorMessage = "Unable to retrieve weather data.";
                    break;
            }

            return ['error' => $errorMessage];
        }
    }

    private function getInseeCode($city)
    {
        try {
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
            
            throw new \Exception("City not found");
        } catch (\Exception $e) {
            return null;
        }
    }    
}
