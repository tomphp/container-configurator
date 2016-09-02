<?php

namespace tests\acceptance;

use TomPHP\ConfigServiceProvider\ConfigureContainer;

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

        ConfigureContainer::fromArray($this->container, $config);
    }
}
