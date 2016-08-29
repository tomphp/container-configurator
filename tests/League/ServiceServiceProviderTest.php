<?php

namespace tests\TomPHP\ConfigServiceProvider\League;

use League\Container\Container;
use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\League\ServiceServiceProvider;
use TomPHP\ConfigServiceProvider\ServiceConfig;

final class ServiceServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    private $container;

    protected function setUp()
    {
        $this->container = new Container();
    }

    public function testItIsAServiceProvider()
    {
        $this->assertInstanceOf(
            'League\Container\ServiceProvider\ServiceProviderInterface',
            new ServiceServiceProvider(new ServiceConfig([]))
        );
    }

    public function testItSetsUpABasicService()
    {
        $config = new ServiceConfig([
            'example_class' => [
                'class' => 'tests\mocks\ExampleClass'
            ]
        ]);

        $this->container->addServiceProvider(new ServiceServiceProvider($config));

        $this->assertInstanceOf(
            'tests\mocks\ExampleClass',
            $this->container->get('example_class')
        );
    }

    public function testItCanCreateSingletonInstances()
    {
        $config = new ServiceConfig([
            'example_class' => [
                'class'     => 'tests\mocks\ExampleClass',
                'singleton' => true,
            ]
        ]);

        $this->container->addServiceProvider(new ServiceServiceProvider($config));

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertSame($instance1, $instance2);
    }

    public function testItCanCreateUniqueInstances()
    {
        $config = new ServiceConfig([
            'example_class' => [
                'class'     => 'tests\mocks\ExampleClass',
                'singleton' => false,
            ]
        ]);

        $this->container->addServiceProvider(new ServiceServiceProvider($config));

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertNotSame($instance1, $instance2);
    }

    public function testItCreatesUniqueInstancesIfSingletonIsNotSpecified()
    {
        $config = new ServiceConfig([
            'example_class' => [
                'class' => 'tests\mocks\ExampleClass',
            ]
        ]);

        $this->container->addServiceProvider(new ServiceServiceProvider($config));

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertNotSame($instance1, $instance2);
    }

    public function testItAddsConstructorArguments()
    {
        $config = new ServiceConfig([
            'example_class' => [
                'class' => 'tests\mocks\ExampleClassWithArgs',
                'arguments' => [
                    'arg1',
                    'arg2',
                ],
            ]
        ]);

        $this->container->addServiceProvider(new ServiceServiceProvider($config));

        $instance = $this->container->get('example_class');

        $this->assertEquals(['arg1', 'arg2'], $instance->getConstructorArgs());
    }

    public function testItCallsSetterMethods()
    {
        $config = new ServiceConfig([
            'example_class' => [
                'class' => 'tests\mocks\ExampleClass',
                'methods' => [
                    'setValue' => ['the value'],
                ],
            ]
        ]);

        $this->container->addServiceProvider(new ServiceServiceProvider($config));

        $instance = $this->container->get('example_class');

        $this->assertEquals('the value', $instance->getValue());
    }

    public function testItThrowsIfAClassDefinitionIsNotCreated()
    {
        $config = new ServiceConfig([
            'example_class' => [
                'class' => function () {
                }
            ]
        ]);

        $this->container->addServiceProvider(new ServiceServiceProvider($config));

        $this->setExpectedException('TomPHP\ConfigServiceProvider\Exception\NotClassDefinitionException');

        $this->container->get('example_class');
    }
}
