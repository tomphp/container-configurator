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

        Configurator::configure($this->container, $config);
    }
}
