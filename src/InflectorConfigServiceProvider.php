<?php

namespace TomPHP\ConfigServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

class InflectorConfigServiceProvider extends AbstractServiceProvider implements
    BootableServiceProviderInterface,
    ConfigurableServiceProvider
{
    /**
     * @var array
     */
    private $config;

    /**
     * @api
     *
     * @param array  $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param array  $config
     */
    public function configure(array $config)
    {
        $this->config = $config;
    }

    public function register()
    {
    }

    public function boot()
    {
        foreach ($this->config as $interface => $config) {
            $this->configureInterface($interface, $config);
        }
    }

    /**
     * @param string $interface
     * @param array  $config
     */
    private function configureInterface($interface, array $config)
    {
        foreach ($config as $method => $args) {
            $this->addInflectorMethod($interface, $method, $args);
        }
    }

    /**
     * @param string $interface
     * @param string $method
     * @param array  $args
     */
    private function addInflectorMethod($interface, $method, array $args)
    {
        $this->getContainer()
            ->inflector($interface)
            ->invokeMethod($method, $args);
    }
}
