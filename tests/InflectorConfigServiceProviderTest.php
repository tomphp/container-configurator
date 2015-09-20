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

    public function testSetsUpAnInflector()
    {
        $config = [
            InflectorInterface::class => [
                'setValue' => ['test_value']
            ]
        ];

        $this->container->addServiceProvider(new InflectorConfigServiceProvider($config));
        $this->container->add('impl', InflectorImplementation::class);

        $this->assertEquals(
            'test_value',
            $this->container->get('impl')->getValue()
        );
    }
}

interface InflectorInterface
{
    public function setValue($value);
}

class InflectorImplementation implements InflectorInterface
{
    private $value;

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
