<?php

namespace TomPHP\ConfigServiceProvider;

final class ServiceDefinition
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $class;

    /**
     * @var bool
     */
    private $singleton;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @var array
     */
    private $methods;

    /**
     * @param string $name
     * @param array  $config
     */
    public function __construct($name, array $config)
    {
        $this->name      = $name;
        $this->class     = isset($config['class']) ? $config['class'] : $name;
        $this->singleton = isset($config['singleton']) ? $config['singleton'] : false;
        $this->arguments = isset($config['arguments']) ? $config['arguments'] : [];
        $this->methods   = isset($config['methods']) ? $config['methods'] : [];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return bool
     */
    public function isSingleton()
    {
        return $this->singleton;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }
}
