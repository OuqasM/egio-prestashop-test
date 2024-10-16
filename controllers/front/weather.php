<?php

require_once _PS_MODULE_DIR_.'ps_weather_forecast/vendor/autoload.php';

use Egio\Ps_Weather_Forecast\Service\WeatherApiService;

class Ps_Weather_ForecastWeatherModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $weatherService = new WeatherApiService();
        $city = Tools::getValue('city', 'Paris');

        if (Tools::isSubmit('submitWeatherForm')) {
            $weatherData = $weatherService->getCurrentWeather($city);
        } else {
            $weatherData = [];
        }

        die($weatherData);
        $this->context->smarty->assign([
            'weather_data' => $weatherData,
            'city' => $city,
        ]);

        $this->setTemplate('module:ps_weather_forecast/views/templates/front/weather.tpl');
    }
}
