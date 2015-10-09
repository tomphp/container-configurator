<?php

namespace TomPHP\ConfigServiceProvider;

use League\Container\Definition\DefinitionInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

final class DIConfigServiceProvider extends AbstractServiceProvider implements
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
        $this->configure($config);
    }

    /**
     * @param array  $config
     */
    public function configure(array $config)
    {
        $this->provides = array_keys($config);
        $this->config = $config;
    }

    public function register()
    {
        foreach ($this->config as $name => $config) {
            $this->registerService($name, $config);
        }
    }

    public function boot()
    {
    }

    /**
     * @param string $name
     * @param array  $config
     */
    private function registerService($name, array $config)
    {
        $singleton = array_key_exists('singleton', $config) && $config['singleton'];

        $service = $this->getContainer()->add($name, $config['class'], $singleton);

        if (!$service) {
            return;
        }

        $this->addConstuctorArguments($service, $config);
        $this->addMethodCalls($service, $config);
    }

    /**
     * @param array $config
     */
    private function addConstuctorArguments(DefinitionInterface $service, array $config)
    {
        if (!isset($config['arguments']) || !is_array($config['arguments'])) {
            return;
        }

        $service->withArguments($config['arguments']);
    }

    /**
     * @param array $config
     */
    private function addMethodCalls(DefinitionInterface $service, array $config)
    {
        if (!isset($config['methods']) || !is_array($config['methods'])) {
            return;
        }

        foreach ($config['methods'] as $method => $args) {
            $service->withMethodCall($method, $args);
        }
    }
}
