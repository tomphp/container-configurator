<?php

namespace tests\TomPHP\ConfigServiceProvider\League;

use League\Container\Container;
use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\InflectorConfig;
use TomPHP\ConfigServiceProvider\League\InflectorServiceProvider;

final class InflectorServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    private $container;

    protected function setUp()
    {
        $this->container = new Container();
    }

    public function testSetsUpAnInflector()
    {
        $config = new InflectorConfig([
            'tests\mocks\ExampleInterface' => [
                'setValue' => ['test_value']
            ]
        ]);

        $this->container->addServiceProvider(new InflectorServiceProvider($config));
        $this->container->add('example', 'tests\mocks\ExampleClass');

        $this->assertEquals(
            'test_value',
            $this->container->get('example')->getValue()
        );
    }
}
