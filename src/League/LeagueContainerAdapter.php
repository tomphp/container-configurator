<?php

namespace TomPHP\ConfigServiceProvider\League;

use Assert\Assertion;
use League\Container\Container;
use TomPHP\ConfigServiceProvider\ApplicationConfig;
use TomPHP\ConfigServiceProvider\ContainerAdapter;
use TomPHP\ConfigServiceProvider\InflectorConfig;
use TomPHP\ConfigServiceProvider\ServiceConfig;

final class LeagueContainerAdapter implements ContainerAdapter
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
        Assertion::string($prefix);

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
