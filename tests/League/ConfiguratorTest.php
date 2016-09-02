<?php

namespace tests\TomPHP\ConfigServiceProvider\League;

use PHPUnit_Framework_TestCase;
use League\Container\Container;
use TomPHP\ConfigServiceProvider\League\Configurator;
use TomPHP\ConfigServiceProvider\ApplicationConfig;
use TomPHP\ConfigServiceProvider\ServiceConfig;
use TomPHP\ConfigServiceProvider\InflectorConfig;

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
        $this->configurator = new Configurator();
        $this->container    = new Container();
        $this->configurator->setContainer($this->container);
    }

    public function testItIsAConfigurator()
    {
        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\Configurator',
            $this->configurator
        );
    }

    public function testItAddsConfigToTheServiceProvider()
    {
        $this->configurator->addApplicationConfig(
            new ApplicationConfig([
                'keyA'   => 'valueA',
                'group1' => ['keyB' => 'valueB'],
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
            new ApplicationConfig([
                'keyA'   => 'valueA',
                'group1' => ['keyB' => 'valueB'],
            ]),
            ''
        );

        $this->assertEquals('valueA', $this->container->get('keyA'));
        $this->assertEquals(['keyB' => 'valueB'], $this->container->get('group1'));
        $this->assertEquals('valueB', $this->container->get('group1.keyB'));
    }

    public function testItAddsServiceConfigToTheServiceProvider()
    {
        $this->configurator->addServiceConfig(
            new ServiceConfig([
                'example_class' => [
                    'class' => 'tests\mocks\ExampleClass',
                ],
            ])
        );

        $this->assertInstanceOf(
            'tests\mocks\ExampleClass',
            $this->container->get('example_class')
        );
    }

    public function testItAddsInflectorConfigToTheServiceProvider()
    {
        $this->configurator->addApplicationConfig(
            new ApplicationConfig(['test_key' => 'test value'])
        );

        $this->configurator->addInflectorConfig(
            new InflectorConfig([
                'tests\mocks\ExampleInterface' => [
                    'setValue' => ['config.test_key'],
                ],
            ])
        );
        $this->container->add('example', 'tests\mocks\ExampleClass');

        $this->assertEquals(
            'test value',
            $this->container->get('example')->getValue()
        );
    }
}
