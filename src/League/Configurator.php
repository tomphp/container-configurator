<?php

namespace TomPHP\ConfigServiceProvider\League;

use League\Container\ServiceProvider\ServiceProviderInterface;
use TomPHP\ConfigServiceProvider\ApplicationConfig;
use TomPHP\ConfigServiceProvider\ContainerConfigurator;
use TomPHP\ConfigServiceProvider\InflectorConfig;
use TomPHP\ConfigServiceProvider\ServiceConfig;

final class Configurator implements ContainerConfigurator
{
    public function addApplicationConfig($container, ApplicationConfig $config, $prefix = 'config')
    {
        $container->addServiceProvider(new ApplicationConfigServiceProvider($config, $prefix));
    }

    public function addServiceConfig($container, ServiceConfig $config)
    {
        $container->addServiceProvider(new ServiceServiceProvider($config));
    }

    public function addInflectorConfig($container, InflectorConfig $config)
    {
        $container->addServiceProvider(new InflectorServiceProvider($config));
    }
}
