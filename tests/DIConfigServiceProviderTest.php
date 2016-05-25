<?php

namespace tests\TomPHP\ConfigServiceProvider;

use League\Container\Container;
use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\DIConfigServiceProvider;
use TomPHP\ConfigServiceProvider\Exception\NotClassDefinitionException;

final class DIConfigServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    private $container;

    protected function setUp()
    {
        $this->container = new Container();
    }

    public function testItIsAConfigurableServiceProvider()
    {
        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\ConfigurableServiceProvider',
            new DIConfigServiceProvider([])
        );
    }

    public function testItSetsUpABasicService()
    {
        $config = [
            'example_class' => [
                'class' => 'tests\mocks\ExampleClass'
            ]
        ];

        $this->container->addServiceProvider(new DIConfigServiceProvider($config));

        $this->assertInstanceOf(
            'tests\mocks\ExampleClass',
            $this->container->get('example_class')
        );
    }

    public function testItCanCreateSingletonInstances()
    {
        $config = [
            'example_class' => [
                'class'     => 'tests\mocks\ExampleClass',
                'singleton' => true,
            ]
        ];

        $this->container->addServiceProvider(new DIConfigServiceProvider($config));

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertSame($instance1, $instance2);
    }

    public function testItCanCreateUniqueInstances()
    {
        $config = [
            'example_class' => [
                'class'     => 'tests\mocks\ExampleClass',
                'singleton' => false,
            ]
        ];

        $this->container->addServiceProvider(new DIConfigServiceProvider($config));

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertNotSame($instance1, $instance2);
    }

    public function testItCreatesUniqueInstancesIfSingletonIsNotSpecified()
    {
        $config = [
            'example_class' => [
                'class' => 'tests\mocks\ExampleClass',
            ]
        ];

        $this->container->addServiceProvider(new DIConfigServiceProvider($config));

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertNotSame($instance1, $instance2);
    }

    public function testItAddsConstructorArguments()
    {
        $config = [
            'example_class' => [
                'class' => 'tests\mocks\ExampleClassWithArgs',
                'arguments' => [
                    'arg1',
                    'arg2',
                ],
            ]
        ];

        $this->container->addServiceProvider(new DIConfigServiceProvider($config));

        $instance = $this->container->get('example_class');

        $this->assertEquals(['arg1', 'arg2'], $instance->getConstructorArgs());
    }

    public function testItCallsSetterMethods()
    {
        $config = [
            'example_class' => [
                'class' => 'tests\mocks\ExampleClass',
                'methods' => [
                    'setValue' => ['the value'],
                ],
            ]
        ];

        $this->container->addServiceProvider(new DIConfigServiceProvider($config));

        $instance = $this->container->get('example_class');

        $this->assertEquals('the value', $instance->getValue());
    }

    public function testItThrowsIfAClassDefinitionIsNotCreated()
    {
        $config = [
            'example_class' => [
                'class' => function () {
                }
            ]
        ];

        $this->container->addServiceProvider(new DIConfigServiceProvider($config));

        $this->setExpectedException('TomPHP\ConfigServiceProvider\Exception\NotClassDefinitionException');

        $this->container->get('example_class');
    }

    public function testCanBeReconfigured()
    {
        $config = [
            'example_class' => [
                'class' => 'tests\mocks\ExampleClass'
            ]
        ];

        $provider = new DIConfigServiceProvider([]);
        $provider->configure($config);

        $this->container->addServiceProvider($provider);
        $this->container->add('example', 'tests\mocks\ExampleClass');

        $this->assertInstanceOf(
            'tests\mocks\ExampleClass',
            $this->container->get('example_class')
        );
    }
}
