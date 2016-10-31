<?php

namespace TomPHP\ContainerConfigurator;

use ArrayIterator;
use Assert\Assertion;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * @internal
 */
final class ServiceConfig implements IteratorAggregate
{
    /**
     * @var ServiceDefinition[]
     */
    private $config = [];

    /**
     * @param array $config
     * @param bool  $singletonDefault
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $config, $singletonDefault = false)
    {
        Assertion::boolean($singletonDefault);

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
