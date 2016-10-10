<?php

namespace TomPHP\ContainerConfigurator;

/**
 * @internal
 */
final class InflectorDefinition
{
    /**
     * @var string
     */
    private $interface;

    /**
     * @var array
     */
    private $methods;

    /**
     * @param string $interface
     * @param array  $methods
     */
    public function __construct($interface, array $methods)
    {
        $this->interface = $interface;
        $this->methods   = $methods;
    }

    /**
     * @return string
     */
    public function getInterface()
    {
        return $this->interface;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }
}
