<?php

namespace TomPHP\ConfigServiceProvider;

use ArrayIterator;
use IteratorAggregate;
use TomPHP\Transform as T;

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
        return array_map(T\callMethod('getName'), $this->config);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->config);
    }
}
