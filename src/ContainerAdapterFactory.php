<?php

namespace TomPHP\ConfigServiceProvider;

use TomPHP\ConfigServiceProvider\Exception\UnknownContainerException;

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
     * @return void
     *
     * @throws UnknownContainerException
     */
    public function create($container)
    {
        $class = '';

        foreach ($this->config as $containerClass => $configuratorClass) {
            if (is_a($container, $containerClass)) {
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
