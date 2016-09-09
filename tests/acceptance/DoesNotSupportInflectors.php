<?php

namespace tests\acceptance;

use TomPHP\ConfigServiceProvider\Configurator;

trait DoesNotSupportInflectors
{
    public function testInflectorsAreUnsupported()
    {
        $this->setExpectedException('TomPHP\ConfigServiceProvider\Exception\UnsupportedFeatureException');

        $config = [
            'di' => [
                'inflectors' => [],
            ],
        ];

        Configurator::apply()->configFromArray($config)->to($this->container);
    }
}
