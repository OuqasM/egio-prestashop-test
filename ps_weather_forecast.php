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
        $this->version = '1.0';
        $this->author = 'Egio Digital';
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Weather Forecast Module');
        $this->description = $this->l('Display weather forecasts using Meteo-Concept API.');
    }

    public function install()
    {
        return parent::install() && 
                $this->registerHook('displayNav2') && 
                Configuration::updateValue('PS_WEATHER_API_KEY', ''); 
    }

    public function uninstall()
    {
        return parent::uninstall() &&
                Configuration::deleteByName('PS_WEATHER_API_KEY') &&
                $this->unregisterHook('displayNav2');
    }

    public function getContent()
    {
        if (Tools::isSubmit('submitPsWeatherForecast')) {
            $apiKey = Tools::getValue('PS_WEATHER_API_KEY');
            Configuration::updateValue('PS_WEATHER_API_KEY', $apiKey); // updateOrCreate value on DB
        }
        
        // render config form
        return $this->renderForm();
    }

    protected function renderForm()
    {
        $helper = new HelperForm();
        $helper->submit_action = 'submitPsWeatherForecast';
        $helper->fields_value['PS_WEATHER_API_KEY'] = Configuration::get('PS_WEATHER_API_KEY');

        return $helper->generateForm([[
            'form' => [
                'legend' => ['title' => $this->l('Settings')],
                'input' => [[
                    'type' => 'text',
                    'label' => $this->l('Meteo-Concept API Key'),
                    'name' => 'PS_WEATHER_API_KEY',
                    'required' => true,
                ]],
                'submit' => ['title' => $this->l('Sauvgarder')],
            ]
        ]]);
    }

    public function hookDisplayNav2($params)
    {
        $this->context->smarty->assign(array(
            'weather_link' => $this->context->link->getModuleLink('ps_weather_forecast', 'weather')
        ));

        return $this->display(__FILE__, 'views/templates/hook/navbar.tpl');
    }
}
