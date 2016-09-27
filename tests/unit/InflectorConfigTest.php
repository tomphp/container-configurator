<?php

namespace tests\unit\TomPHP\ContainerConfigurator;

use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\InflectorConfig;
use TomPHP\ContainerConfigurator\InflectorDefinition;

final class InflectorConfigTest extends PHPUnit_Framework_TestCase
{
    public function testItMapsTheConfigArrayToInflectorDefinitions()
    {
        $interface = 'example_interface';
        $methods   = ['method1' => ['arg1', 'arg2']];

        $subject = new InflectorConfig([$interface => $methods]);

        assertEquals(
            [new InflectorDefinition($interface, $methods)],
            iterator_to_array($subject)
        );
    }
}
