<?php

namespace TomPHP\ConfigServiceProvider\Pimple;

use Pimple\Container;
use ReflectionClass;
use TomPHP\ConfigServiceProvider\ApplicationConfig;
use TomPHP\ConfigServiceProvider\ContainerConfigurator;
use TomPHP\ConfigServiceProvider\Exception\UnsupportedFeatureException;
use TomPHP\ConfigServiceProvider\InflectorConfig;
use TomPHP\ConfigServiceProvider\ServiceConfig;
use TomPHP\ConfigServiceProvider\ServiceDefinition;

final class Configurator implements ContainerConfigurator
{
    /**
     * @var Container
     */
    private $container;

    public function addApplicationConfig($container, ApplicationConfig $config, $prefix = 'config')
    {
        if (!empty($prefix)) {
            $prefix .= $config->getSeparator();
        }

        foreach ($config as $key => $value) {
            $container[$prefix . $key] = $value;
        }
    }

    public function addServiceConfig($container, ServiceConfig $config)
    {
        $this->container = $container;

        foreach ($config as $definition) {
            $this->addServiceToContainer($definition);
        }
    }

    public function addInflectorConfig($container, InflectorConfig $config)
    {
        throw UnsupportedFeatureException::forInflectors('Pimple');
    }

    private function addServiceToContainer(ServiceDefinition $definition)
    {
        $factory = function () use ($definition) {
            $reflection = new ReflectionClass($definition->getClass());

            $instance = $reflection->newInstanceArgs($this->resolveArguments($definition->getArguments()));

            foreach ($definition->getMethods() as $name => $args) {
                call_user_func_array([$instance, $name], $this->resolveArguments($args));
            }

            return $instance;
        };

        if (!$definition->isSingleton()) {
            $factory = $this->container->factory($factory);
        }

        $this->container[$definition->getName()] = $factory;
    }

    private function resolveArguments(array $arguments)
    {
        return array_map(
            function ($argument) {
                return isset($this->container[$argument]) ? $this->container[$argument] : $argument;
            },
            $arguments
        );
    }
}
