<?php

namespace tests\mocks;

use TomPHP\ConfigServiceProvider\ApplicationConfig;
use TomPHP\ConfigServiceProvider\Configurator;
use TomPHP\ConfigServiceProvider\InflectorConfig;
use TomPHP\ConfigServiceProvider\ServiceConfig;

final class ExampleConfigurator implements Configurator
{
    private $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function addApplicationConfig(ApplicationConfig $config, $prefix = 'config')
    {
    }

    public function addServiceConfig(ServiceConfig $config)
    {
    }

    public function addInflectorConfig(InflectorConfig $config)
    {
    }
}
