<?php

namespace TomPHP\ConfigServiceProvider\League;

use TomPHP\ConfigServiceProvider\ContainerConfigurator;
use TomPHP\ConfigServiceProvider\Config;
use League\Container\ServiceProvider\AbstractServiceProvider;

final class Configurator implements ContainerConfigurator
{
    /** @var ConfigServiceProvider */
    private $provider;

    public function addConfig(Config $config, $prefix = 'config')
    {
        $this->provider = new ConfigServiceProvider($config, $prefix);
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
