<?php

namespace TomPHP\ContainerConfigurator\Pimple;

use Assert\Assertion;
use Closure;
use Pimple\Container;
use TomPHP\ContainerConfigurator\ApplicationConfig;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\ContainerConfigurator\ContainerAdapter;
use TomPHP\ContainerConfigurator\InflectorConfig;
use TomPHP\ContainerConfigurator\InflectorDefinition;
use TomPHP\ContainerConfigurator\ServiceConfig;
use TomPHP\ContainerConfigurator\ServiceDefinition;

/**
 * @internal
 */
final class PimpleContainerAdapter implements ContainerAdapter
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Closure
     */
    private $inflectors = [];

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

        if (!empty($prefix)) {
            $prefix .= $config->getSeparator();
        }

        foreach ($config as $key => $value) {
            $this->container[$prefix . $key] = $value;
        }
    }

    public function addServiceConfig(ServiceConfig $config)
    {
        foreach ($config as $definition) {
            $this->addServiceToContainer($definition);
        }
    }

    public function addInflectorConfig(InflectorConfig $config)
    {
        foreach ($config as $definition) {
            $this->inflectors[$definition->getInterface()] = $this->createInflector($definition);
        }
    }

    private function addServiceToContainer(ServiceDefinition $definition)
    {
        $factory = $this->createFactory($definition);

        if (!$definition->isSingleton()) {
            $factory = $this->container->factory($factory);
        }

        $this->container[$definition->getName()] = $factory;
    }

    /**
     * @param ServiceDefinition $definition
     *
     * @return Closure
     */
    private function createFactory(ServiceDefinition $definition)
    {
        if ($definition->isFactory()) {
            return $this->applyInflectors($this->createFactoryFactory($definition));
        }

        if ($definition->isAlias()) {
            return $this->createAliasFactory($definition);
        }

        return $this->applyInflectors($this->createInstanceFactory($definition));
    }

    /**
     * @param ServiceDefinition $definition
     *
     * @return Closure
     */
    private function createFactoryFactory(ServiceDefinition $definition)
    {
        return function () use ($definition) {
            $className = $definition->getClass();
            $factory   = new $className();

            return $factory(...$this->resolveArguments($definition->getArguments()));
        };
    }

    /**
     * @param ServiceDefinition $definition
     *
     * @return Closure
     */
    private function createAliasFactory(ServiceDefinition $definition)
    {
        return function () use ($definition) {
            return $this->container[$definition->getClass()];
        };
    }

    /**
     * @param ServiceDefinition $definition
     *
     * @return Closure
     */
    private function createInstanceFactory(ServiceDefinition $definition)
    {
        return function () use ($definition) {
            $className = $definition->getClass();
            $instance  = new $className(...$this->resolveArguments($definition->getArguments()));

            foreach ($definition->getMethods() as $name => $args) {
                $instance->$name(...$this->resolveArguments($args));
            }

            return $instance;
        };
    }

    /**
     * @param InflectorDefinition $definition
     *
     * @return Closure
     */
    private function createInflector(InflectorDefinition $definition)
    {
        return function ($subject) use ($definition) {
            foreach ($definition->getMethods() as $method => $arguments) {
                $subject->$method(...$this->resolveArguments($arguments));
            }
        };
    }

    /**
     * @param Closure $factory
     *
     * @return Closure
     */
    private function applyInflectors(Closure $factory)
    {
        return function () use ($factory) {
            $instance = $factory();

            foreach ($this->inflectors as $interface => $inflector) {
                if ($instance instanceof $interface) {
                    $inflector($instance);
                }
            }

            return $instance;
        };
    }

    /**
     * @param array $arguments
     *
     * @return array
     */
    private function resolveArguments(array $arguments)
    {
        return array_map(
            function ($argument) {
                if (!is_string($argument)) {
                    return $argument;
                }

                if ($argument === Configurator::container()) {
                    return $this->container;
                }

                if (isset($this->container[$argument])) {
                    return $this->container[$argument];
                }

                return $argument;
            },
            $arguments
        );
    }
}
