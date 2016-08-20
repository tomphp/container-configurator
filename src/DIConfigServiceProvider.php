<?php

namespace TomPHP\ConfigServiceProvider;

use League\Container\Definition\ClassDefinition;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use TomPHP\ConfigServiceProvider\Exception\NotClassDefinitionException;

final class DIConfigServiceProvider extends AbstractServiceProvider
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

    /**
     * @param array  $config
     */
    public function configure(array $config)
    {
        $this->provides = array_keys($config);
        $this->config   = $config;
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
            $definition->getKey(),
            $definition->getClass(),
            $definition->isSingleton()
        );

        if (!$service instanceof ClassDefinition) {
            throw new NotClassDefinitionException(sprintf(
                'DI definition for %s does not create a class definition',
                $definition->getKey()
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
