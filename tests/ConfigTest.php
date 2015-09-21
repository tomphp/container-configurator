<?php

namespace tests\TomPHP\ConfigServiceProvider;

use League\Container\Container;
use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Config;
use tests\mocks\ExampleClass;
use tests\mocks\ExampleInterface;

class ConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    private $container;

    protected function setUp()
    {
        $this->container = new Container();

        $config = [
            'test_key' => 'test value',

            'inflectors' => [
                'tests\mocks\ExampleInterface' => [
                    'setValue' => ['config.test_key']
                ]
            ]
        ];

        Config::addToContainer($this->container, $config);

        $this->container->add('example', 'tests\mocks\ExampleClass');
    }

    public function testItCanAUseCustomPrefix()
    {
        $this->assertEquals(
            'test value',
            $this->container->get('example')->getValue()
        );
    }
}
