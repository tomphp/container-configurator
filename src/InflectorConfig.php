<?php

namespace TomPHP\ContainerConfigurator;

use ArrayIterator;
use IteratorAggregate;

/**
 * @internal
 */
final class InflectorConfig implements IteratorAggregate
{
    /**
     * @var array
     */
    private $inflectors;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->inflectors = [];

        foreach ($config as $interfaceName => $methods) {
            $this->inflectors[] = new InflectorDefinition(
                $interfaceName,
                $methods
            );
        }
    }

    public function getIterator()
    {
        return new ArrayIterator($this->inflectors);
    }
}
