<?php

namespace TomPHP\ContainerConfigurator\League;

use League\Container\Definition\ClassDefinition;
use League\Container\ServiceProvider\AbstractServiceProvider;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\ContainerConfigurator\Exception\NotClassDefinitionException;
use TomPHP\ContainerConfigurator\ServiceConfig;
use TomPHP\ContainerConfigurator\ServiceDefinition;

/**
 * @internal
 */
final class ServiceServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    private $config;

    /**
     * @api
     *
     * @param ServiceConfig $config
     */
    public function __construct(ServiceConfig $config)
    {
        $this->config   = $config;
        $this->provides = $config->getKeys();
    }

    public function register()
    {
        foreach ($this->config as $config) {
            $this->registerService($config);
        }
    }

    /**
     * @param ServiceDefinition $definition
     *
     * @throws NotClassDefinitionException
     *
     * @return void
     */
    private function registerService(ServiceDefinition $definition)
    {
        if ($definition->isFactory()) {
            $this->getContainer()->add(
                $definition->getName(),
                $this->createFactoryFactory($definition),
                $definition->isSingleton()
            );

            return;
        }

        if ($definition->isAlias()) {
            $this->getContainer()->add(
                $definition->getName(),
                $this->createAliasFactory($definition)
            );

            return;
        }

        $service = $this->getContainer()->add(
            $definition->getName(),
            $definition->getClass(),
            $definition->isSingleton()
        );

        if (!$service instanceof ClassDefinition) {
            throw NotClassDefinitionException::fromServiceName($definition->getName());
        }

        $service->withArguments($this->injectContainer($definition->getArguments()));
        $this->addMethodCalls($service, $definition);
    }

    /**
     * @param ClassDefinition   $service
     * @param ServiceDefinition $definition
     */
    private function addMethodCalls(ClassDefinition $service, ServiceDefinition $definition)
    {
        foreach ($definition->getMethods() as $method => $args) {
            $service->withMethodCall($method, $this->injectContainer($args));
        }
    }

    /**
     * @param ServiceDefinition $definition
     *
     * @return \Closure
     */
    private function createAliasFactory(ServiceDefinition $definition)
    {
        return function () use ($definition) {
            return $this->getContainer()->get($definition->getClass());
        };
    }

    /**
     * @param ServiceDefinition $definition
     *
     * @return \Closure
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
     * @param array $arguments
     *
     * @return array
     */
    private function injectContainer(array $arguments)
    {
        return array_map(
            function ($argument) {
                return ($argument === Configurator::container())
                    ? $this->container
                    : $argument;
            },
            $arguments
        );
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
                if ($argument === Configurator::container()) {
                    return $this->container;
                }

                if ($this->container->has($argument)) {
                    return $this->container->get($argument);
                }

                return $argument;
            },
            $arguments
        );
    }
}
