<?php

require_once _PS_MODULE_DIR_ . 'ps_weather_forecast/vendor/autoload.php';

use Egio\Ps_Weather_Forecast\Service\WeatherApiService;

class Ps_Weather_ForecastWeatherModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $weatherService = new WeatherApiService();
        $city = Tools::getValue('city', 'Rabat');
        $forecastType = Tools::getValue('forecastType', '');

        if (Tools::isSubmit('submitWeatherForm')) {
            switch ($forecastType) {
                case 'today':
                    $weatherData = $weatherService->getCurrentWeather($city);
                    break;
                case 'nextdays':
                    $weatherData = $weatherService->getForecastByDay($city);
                    break;
                case 'nexthours':
                    $weatherData = $weatherService->getForecastByQuarterDay($city);
                    break;
                default:
                    $weatherData = ['error' => "Invalid Forecast Type."];
                    break;
            }
        } else {
            $weatherData = [];
        }

        $this->context->smarty->assign([
            'weather_data' => $weatherData,
            'city' => $city,
            'forecastType' => $forecastType,
        ]);

        $this->setTemplate('module:ps_weather_forecast/views/templates/front/weather.tpl');
    }
}
