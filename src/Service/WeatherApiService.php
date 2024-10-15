<?php

namespace PsWeatherForecast\Service;

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
        return $this->makeRequest('forecast/daily', $city);
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
            return ['error' => "Ville not found."];
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
                    $errorMessage = "Il manque un paramètre ou la valeur est incorrecte";
                    break;
                case 401:
                    $errorMessage = "Vous n'êtes pas authentifié(e)";
                    break;
                case 403:
                    $errorMessage = "Vous n'avez pas accès à cette page";
                    break;
                case 404:
                    $errorMessage = "La page demandée n'existe pas";
                    break;
                case 500:
                    $errorMessage = "Une erreur est survenue, merci de réessayer plus tard";
                    break;
                case 503:
                    $errorMessage = "L'API est momentanément indisponible, merci de réessayer dans quelques minutes";
                    break;
                default:
                    $errorMessage = "Impossible de récupérer les données météorologiques.";
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
