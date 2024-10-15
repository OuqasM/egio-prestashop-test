<?php

namespace PsWeatherForecast\Controller\Front;

use PsWeatherForecast\Service\WeatherApiService;
use Symfony\Component\HttpFoundation\Request;
use Tools;

class WeatherController extends \ModuleFrontController
{
    public function initContent()
    {
        dd("salam");
        parent::initContent();

        $weatherService = new WeatherApiService();
        $city = Tools::getValue('city', 'Paris');

        if (Tools::isSubmit('submitWeatherForm')) {
            $weatherData = $weatherService->getCurrentWeather($city);
        } else {
            $weatherData = [];
        }

        $this->context->smarty->assign([
            'weather_data' => $weatherData,
            'city' => $city,
        ]);

        $this->setTemplate('module:ps_weather_forecast/views/templates/front/weather.tpl');
    }
}
