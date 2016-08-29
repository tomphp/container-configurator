<?php

namespace TomPHP\ConfigServiceProvider\League;

use League\Container\Definition\ClassDefinition;
use League\Container\ServiceProvider\AbstractServiceProvider;
use TomPHP\ConfigServiceProvider\Exception\NotClassDefinitionException;
use TomPHP\ConfigServiceProvider\ServiceConfig;
use TomPHP\ConfigServiceProvider\ServiceDefinition;

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
     * @param ServiceDefinition  $definition
     */
    private function registerService(ServiceDefinition $definition)
    {
        $service = $this->getContainer()->add(
            $definition->getName(),
            $definition->getClass(),
            $definition->isSingleton()
        );

        if (!$service instanceof ClassDefinition) {
            throw new NotClassDefinitionException(sprintf(
                'DI definition for %s does not create a class definition',
                $definition->getName()
            ));
        }

        $service->withArguments($definition->getArguments());
        $this->addMethodCalls($service, $definition);
    }

    /**
     * @param ClassDefinition   $service
     * @param ServiceDefinition $definition
     */
    private function addMethodCalls(ClassDefinition $service, ServiceDefinition $definition)
    {
        foreach ($definition->getMethods() as $method => $args) {
            $service->withMethodCall($method, $args);
        }
    }
}
