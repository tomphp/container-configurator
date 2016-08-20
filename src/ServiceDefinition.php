<?php

namespace TomPHP\ConfigServiceProvider;

final class ServiceDefinition
{
    /**
     * @var string
     */
    private $key;

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
     * @param string $key
     * @param array  $config
     */
    public function __construct($key, array $config)
    {
        $this->key       = $key;
        $this->class     = isset($config['class']) ? $config['class'] : $key;
        $this->singleton = isset($config['singleton']) ? $config['singleton'] : false;
        $this->arguments = isset($config['arguments']) ? $config['arguments'] : [];
        $this->methods   = isset($config['methods']) ? $config['methods'] : [];
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return boolean
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
