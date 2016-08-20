<?php

namespace tests\TomPHP\ConfigServiceProvider;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\ServiceConfig;
use TomPHP\ConfigServiceProvider\ServiceDefinition;

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

        $this->assertEquals(
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

        $this->assertEquals(['service1', 'service2'], $config->getKeys());
    }
}
