<?php

namespace tests\TomPHP\ConfigServiceProvider;

use League\Container\Container;
use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\InflectorConfigServiceProvider;

class InflectorConfigServiceProviderTest extends PHPUnit_Framework_TestCase
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
            new InflectorConfigServiceProvider([])
        );
    }

    public function testSetsUpAnInflector()
    {
        $config = [
            'tests\mocks\ExampleInterface' => [
                'setValue' => ['test_value']
            ]
        ];

        $this->container->addServiceProvider(new InflectorConfigServiceProvider($config));
        $this->container->add('example', 'tests\mocks\ExampleClass');

        $this->assertEquals(
            'test_value',
            $this->container->get('example')->getValue()
        );
    }

    public function testCanBeReconfigured()
    {
        $config = [
            'tests\mocks\ExampleInterface' => [
                'setValue' => ['test_value']
            ]
        ];

        $provider = new InflectorConfigServiceProvider([]);
        $provider->configure($config);

        $this->container->addServiceProvider($provider);
        $this->container->add('example', 'tests\mocks\ExampleClass');

        $this->assertEquals(
            'test_value',
            $this->container->get('example')->getValue()
        );
    }
}
