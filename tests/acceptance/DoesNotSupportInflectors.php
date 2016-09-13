<?php

namespace tests\acceptance;

use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\ContainerConfigurator\Exception\UnsupportedFeatureException;

trait DoesNotSupportInflectors
{
    public function testInflectorsAreUnsupported()
    {
        $this->expectException(UnsupportedFeatureException::class);

        $config = [
            'di' => [
                'inflectors' => [],
            ],
        ];

        Configurator::apply()->configFromArray($config)->to($this->container);
    }
}
