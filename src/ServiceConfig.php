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

    public function __construct(array $config)
    {
        foreach ($config as $key => $serviceConfig) {
            $this->config[] = new ServiceDefinition($key, $serviceConfig);
        }
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return array_map(
            function (ServiceDefinition $definition) {
                return $definition->getKey();
            },
            $this->config
        );
    }

    public function getIterator()
    {
        return new ArrayIterator($this->config);
    }
}
