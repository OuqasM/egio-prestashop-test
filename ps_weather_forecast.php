<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Ps_Weather_Forecast extends Module
{
    public function __construct()
    {
        $this->name = 'ps_weather_forecast';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'El Mahdi Ouqas';
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Weather Forecast Module');
        $this->description = $this->l('Display weather forecasts using Meteo-Concept API.');
    }

    public function install()
    {
        return parent::install() && 
               $this->registerHook('displayHeader') && 
               Configuration::updateValue('PS_WEATHER_API_KEY', ''); 
    }

    public function uninstall()
    {
        return parent::uninstall() && Configuration::deleteByName('PS_WEATHER_API_KEY');
    }
}
