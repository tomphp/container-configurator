<?php

namespace TomPHP\ConfigServiceProvider;

use Assert\Assertion;

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
     * @param bool   $singletonDefault
     */
    public function __construct($name, array $config, $singletonDefault = false)
    {
        Assertion::string($name);
        Assertion::boolean($singletonDefault);

        $this->name      = $name;
        $this->class     = isset($config['class']) ? $config['class'] : $name;
        $this->singleton = isset($config['singleton']) ? $config['singleton'] : $singletonDefault;
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
