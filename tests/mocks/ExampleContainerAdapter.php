<?php

namespace tests\mocks;

use TomPHP\ContainerConfigurator\ApplicationConfig;
use TomPHP\ContainerConfigurator\ContainerAdapter;
use TomPHP\ContainerConfigurator\InflectorConfig;
use TomPHP\ContainerConfigurator\ServiceConfig;

final class ExampleContainerAdapter implements ContainerAdapter
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
