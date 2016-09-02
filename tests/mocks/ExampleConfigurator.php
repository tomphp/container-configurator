<?php

namespace tests\mocks;

use TomPHP\ConfigServiceProvider\ApplicationConfig;
use TomPHP\ConfigServiceProvider\Configurator;
use TomPHP\ConfigServiceProvider\InflectorConfig;
use TomPHP\ConfigServiceProvider\ServiceConfig;

final class ExampleConfigurator implements Configurator
{
    public function addApplicationConfig($container, ApplicationConfig $config, $prefix = 'config')
    {
    }

    public function addServiceConfig($container, ServiceConfig $config)
    {
    }

    public function addInflectorConfig($container, InflectorConfig $config)
    {
    }
}
