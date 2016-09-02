<?php

namespace TomPHP\ConfigServiceProvider;

use ArrayIterator;
use IteratorAggregate;

final class ServiceConfig implements IteratorAggregate
{
    /**
     * @var ServiceDefinition[]
     */
    private $config = [];

    /**
     * @param array $config
     * @param bool  $singletonDefault
     */
    public function __construct(array $config, $singletonDefault = false)
    {
        foreach ($config as $key => $serviceConfig) {
            $this->config[] = new ServiceDefinition($key, $serviceConfig, $singletonDefault);
        }
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return array_map(
            function (ServiceDefinition $definition) {
                return $definition->getName();
            },
            $this->config
        );
    }

    public function getIterator()
    {
        return new ArrayIterator($this->config);
    }
}
