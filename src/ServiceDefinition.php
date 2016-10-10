<?php

namespace TomPHP\ContainerConfigurator;

use Assert\Assertion;
use InvalidArgumentException;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;

/**
 * @internal
 */
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
    private $isSingleton;

    /**
     * @var bool
     */
    private $isFactory;

    /**
     * @var bool
     */
    private $isAlias;

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
     *
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     */
    public function __construct($name, array $config, $singletonDefault = false)
    {
        Assertion::string($name);
        Assertion::boolean($singletonDefault);

        $this->name        = $name;
        $this->class       = $this->className($name, $config);
        $this->isSingleton = isset($config['singleton']) ? $config['singleton'] : $singletonDefault;
        $this->isFactory   = isset($config['factory']);
        $this->isAlias     = isset($config['service']);
        $this->arguments   = isset($config['arguments']) ? $config['arguments'] : [];
        $this->methods     = isset($config['methods']) ? $config['methods'] : [];
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
        return $this->isSingleton;
    }

    /**
     * @return bool
     */
    public function isFactory()
    {
        return $this->isFactory;
    }

    /**
     * @return bool
     */
    public function isAlias()
    {
        return $this->isAlias;
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

    /**
     * @param string $name
     * @param array  $config
     *
     * @throws InvalidConfigException
     *
     * @return string
     */
    private function className($name, array $config)
    {
        if (isset($config['class']) && isset($config['factory'])) {
            throw InvalidConfigException::fromNameWhenClassAndFactorySpecified($name);
        }

        if (isset($config['class']) && isset($config['service'])) {
            throw InvalidConfigException::fromNameWhenClassAndServiceSpecified($name);
        }

        if (isset($config['factory']) && isset($config['service'])) {
            throw InvalidConfigException::fromNameWhenFactoryAndServiceSpecified($name);
        }

        if (isset($config['service'])) {
            return $config['service'];
        }

        if (isset($config['class'])) {
            return $config['class'];
        }

        if (isset($config['factory'])) {
            return $config['factory'];
        }

        return $name;
    }
}
