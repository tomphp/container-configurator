<?php

namespace tests\unit\TomPHP\ContainerConfigurator;

use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\ServiceConfig;
use TomPHP\ContainerConfigurator\ServiceDefinition;

final class ServiceConfigTest extends PHPUnit_Framework_TestCase
{
    public function testFormatsASingleService()
    {
        $serviceConfig = [
            'class'     => __CLASS__,
            'singleton' => false,
            'arguments' => ['argument1', 'argument2'],
            'method'    => ['setSomething' => ['value']],
        ];

        $config = new ServiceConfig(['service_name' => $serviceConfig]);

        assertEquals(
            [new ServiceDefinition('service_name', $serviceConfig)],
            iterator_to_array($config)
        );
    }

    public function testItProvidesAListOfKeys()
    {
        $serviceConfig = [
            'class'     => __CLASS__,
            'singleton' => false,
            'arguments' => ['argument1', 'argument2'],
            'method'    => ['setSomething' => ['value']],
        ];

        $config = new ServiceConfig([
            'service1' => $serviceConfig,
            'service2' => $serviceConfig,
        ]);

        assertEquals(['service1', 'service2'], $config->getKeys());
    }

    public function testDefaultValueForSingletonCanBeSetToTrue()
    {
        $serviceConfig = ['class' => __CLASS__];

        $config = new ServiceConfig(['service_name' => $serviceConfig], true);

        assertTrue(iterator_to_array($config)[0]->isSingleton());
    }
}
