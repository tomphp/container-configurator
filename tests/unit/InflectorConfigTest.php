<?php

namespace tests\unit\TomPHP\ConfigServiceProvider;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\InflectorConfig;
use TomPHP\ConfigServiceProvider\InflectorDefinition;

final class InflectorConfigTest extends PHPUnit_Framework_TestCase
{
    public function testItMapsTheConfigArrayToInflectorDefinitions()
    {
        $interface = 'example_interface';
        $methods = ['method1' => ['arg1', 'arg2']];

        $subject = new InflectorConfig([$interface => $methods]);

        $this->assertEquals(
            [new InflectorDefinition($interface, $methods)],
            iterator_to_array($subject)
        );
    }
}
