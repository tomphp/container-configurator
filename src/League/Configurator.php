<?php

namespace TomPHP\ConfigServiceProvider\League;

use League\Container\ServiceProvider\AbstractServiceProvider;
use TomPHP\ConfigServiceProvider\ApplicationConfig;
use TomPHP\ConfigServiceProvider\ContainerConfigurator;
use TomPHP\ConfigServiceProvider\DIConfigServiceProvider;
use TomPHP\ConfigServiceProvider\InflectorConfig;
use TomPHP\ConfigServiceProvider\InflectorConfigServiceProvider;
use TomPHP\ConfigServiceProvider\ServiceConfig;

final class Configurator implements ContainerConfigurator
{
    /**
     * @var ServiceProviderInterface[]
     */
    private $providers = [];

    public function addConfig(ApplicationConfig $config, $prefix = 'config')
    {
        $this->providers[] = new ApplicationConfigServiceProvider($config, $prefix);
    }

    public function addServiceConfig(ServiceConfig $config)
    {
        $this->providers[] = new DIConfigServiceProvider($config);
    }

    public function addInflectorConfig(InflectorConfig $config)
    {
        $this->providers[] = new InflectorConfigServiceProvider($config);
    }

    /**
     * @internal
     *
     * @return ServiceProviderInterface
     */
    public function getServiceProvider()
    {
        return new AggregateServiceProvider($this->providers);
    }
}
