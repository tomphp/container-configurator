<?php

namespace TomPHP\ConfigServiceProvider\League;

use League\Container\Container;
use TomPHP\ConfigServiceProvider\ApplicationConfig;
use TomPHP\ConfigServiceProvider\Configurator as ConfiguratorInterface;
use TomPHP\ConfigServiceProvider\InflectorConfig;
use TomPHP\ConfigServiceProvider\ServiceConfig;

final class Configurator implements ConfiguratorInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function addApplicationConfig(ApplicationConfig $config, $prefix = 'config')
    {
        $this->container->addServiceProvider(new ApplicationConfigServiceProvider($config, $prefix));
    }

    public function addServiceConfig(ServiceConfig $config)
    {
        $this->container->addServiceProvider(new ServiceServiceProvider($config));
    }

    public function addInflectorConfig(InflectorConfig $config)
    {
        $this->container->addServiceProvider(new InflectorServiceProvider($config));
    }
}
