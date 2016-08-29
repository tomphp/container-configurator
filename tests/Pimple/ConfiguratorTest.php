<?php

namespace tests\TomPHP\ConfigServiceProvider\Pimple;

use PHPUnit_Framework_TestCase;
use Pimple\Container;
use TomPHP\ConfigServiceProvider\ApplicationConfig;
use TomPHP\ConfigServiceProvider\Pimple\Configurator;
use TomPHP\ConfigServiceProvider\ServiceConfig;

final class ConfiguratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Configurator
     */
    private $configurator;

    /**
     * @var Container
     */
    private $container;

    protected function setUp()
    {
        $this->container    = new PimpleContainerWrapper();
        $this->configurator = new Configurator($this->container);
    }

    public function testItIsAContainerConfigurator()
    {
        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\ContainerConfigurator',
            $this->configurator
        );
    }

    public function testItAddsConfigToTheServiceProvider()
    {
        $this->configurator->addApplicationConfig(
            $this->container,
            new ApplicationConfig([
                'keyA'   => 'valueA',
                'group1' => ['keyB' => 'valueB']
            ]),
            'settings'
        );

        $this->assertEquals('valueA', $this->container->get('settings.keyA'));
        $this->assertEquals(['keyB' => 'valueB'], $this->container->get('settings.group1'));
        $this->assertEquals('valueB', $this->container->get('settings.group1.keyB'));
    }

    public function testItAddsConfigToTheServiceProviderWithNoPrefix()
    {
        $this->configurator->addApplicationConfig(
            $this->container,
            new ApplicationConfig([
                'keyA'   => 'valueA',
                'group1' => ['keyB' => 'valueB']
            ]),
            ''
        );

        $this->assertEquals('valueA', $this->container->get('keyA'));
        $this->assertEquals(['keyB' => 'valueB'], $this->container->get('group1'));
        $this->assertEquals('valueB', $this->container->get('group1.keyB'));
    }

    // Services

    public function testItAddsServiceConfigToTheServiceProvider()
    {
        $config = new ServiceConfig([
            'example_class' => [
                'class' => 'tests\mocks\ExampleClass',
            ],
        ]);
        $this->configurator->addServiceConfig($this->container, $config);

        $this->assertInstanceOf(
            'tests\mocks\ExampleClass',
            $this->container->get('example_class')
        );
    }

    public function testItCanCreateUniqueServiceInstances()
    {
        $config = new ServiceConfig([
            'example_class' => [
                'class'     => 'tests\mocks\ExampleClass',
                'singleton' => false,
            ]
        ]);
        $this->configurator->addServiceConfig($this->container, $config);

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertNotSame($instance1, $instance2);
    }

    public function testItCanCreateSingletonServiceInstances()
    {
        $config = new ServiceConfig([
            'example_class' => [
                'class'     => 'tests\mocks\ExampleClass',
                'singleton' => true,
            ]
        ]);
        $this->configurator->addServiceConfig($this->container, $config);

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertSame($instance1, $instance2);
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
        $this->configurator->addServiceConfig($this->container, $config);

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
        $this->configurator->addServiceConfig($this->container, $config);

        $instance = $this->container->get('example_class');

        $this->assertEquals('the value', $instance->getValue());
    }

    /*
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
     */
}
