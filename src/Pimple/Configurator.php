<?php

namespace TomPHP\ConfigServiceProvider\Pimple;

use Pimple\Container;
use ReflectionClass;
use TomPHP\ConfigServiceProvider\ApplicationConfig;
use TomPHP\ConfigServiceProvider\ContainerConfigurator;
use TomPHP\ConfigServiceProvider\InflectorConfig;
use TomPHP\ConfigServiceProvider\ServiceConfig;
use TomPHP\ConfigServiceProvider\Exception\UnsupportedFeatureException;

final class Configurator implements ContainerConfigurator
{
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
        foreach ($config as $definition) {
            $factory = function () use ($definition) {
                $reflection = new ReflectionClass($definition->getClass());

                $instance = $reflection->newInstanceArgs($definition->getArguments());

                foreach ($definition->getMethods() as $name => $args) {
                    call_user_func_array([$instance, $name], $args);
                }

                return $instance;
            };

            if (!$definition->isSingleton()) {
                $factory = $container->factory($factory);
            }

            $container[$definition->getName()] = $factory;
        }
    }

    public function addInflectorConfig($container, InflectorConfig $config)
    {
        throw UnsupportedFeatureException::forInflectors('Pimple');
    }
}
