<?php

namespace TomPHP\ConfigServiceProvider\League;

use TomPHP\ConfigServiceProvider\ContainerConfigurator;
use TomPHP\ConfigServiceProvider\ApplicationConfig;
use League\Container\ServiceProvider\AbstractServiceProvider;

final class Configurator implements ContainerConfigurator
{
    /** @var ApplicationConfigServiceProvider */
    private $provider;

    public function addConfig(ApplicationConfig $config, $prefix = 'config')
    {
        $this->provider = new ApplicationConfigServiceProvider($config, $prefix);
    }

    /**
     * @internal
     *
     * @return ServiceProviderInterface
     */
    public function getServiceProvider()
    {
        return $this->provider;
    }
}
