<?php

namespace TomPHP\ContainerConfigurator;

use TomPHP\ContainerConfigurator\Exception\UnknownContainerException;

final class ContainerAdapterFactory
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param object $container
     *
     * @throws UnknownContainerException
     *
     * @return void
     */
    public function create($container)
    {
        $class = '';

        foreach ($this->config as $containerClass => $configuratorClass) {
            if ($container instanceof $containerClass) {
                $class = $configuratorClass;
                break;
            }
        }

        if (!$class) {
            throw UnknownContainerException::fromContainerName(
                get_class($container),
                array_keys($this->config)
            );
        }

        $instance = new $class();
        $instance->setContainer($container);

        return $instance;
    }
}
