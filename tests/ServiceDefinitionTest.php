<?php

namespace tests\TomPHP\ConfigServiceProvider;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\ServiceDefinition;

final class ServiceDefinitionTest extends PHPUnit_Framework_TestCase
{
    public function testItCreatesFromConfig()
    {
        $config = [
            'class'     => __CLASS__,
            'singleton' => false,
            'arguments' => ['argument1', 'argument2'],
            'methods'   => ['setSomething' => ['value']],
        ];

        $definition = new ServiceDefinition('service_name', $config);

        $this->assertEquals('service_name', $definition->getName());
        $this->assertEquals(__CLASS__, $definition->getClass());
        $this->assertFalse($definition->isSingleton());
        $this->assertEquals(['argument1', 'argument2'], $definition->getArguments());
        $this->assertEquals(['setSomething' => ['value']], $definition->getMethods());
    }

    public function testClassDefaultsToKey()
    {
        $definition = new ServiceDefinition('service_name', []);

        $this->assertEquals('service_name', $definition->getClass());
    }

    public function testSingletonDefaultsToFalse()
    {
        $definition = new ServiceDefinition('service_name', []);

        $this->assertFalse($definition->isSingleton());
    }

    public function testArgumentsDefaultToAnEmptyList()
    {
        $definition = new ServiceDefinition('service_name', []);

        $this->assertEquals([], $definition->getArguments());
    }

    public function testMethodsDefaultToAnEmptyList()
    {
        $definition = new ServiceDefinition('service_name', []);

        $this->assertEquals([], $definition->getMethods());
    }
}
