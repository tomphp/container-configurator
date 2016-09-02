<?php

namespace tests\acceptance;

use TomPHP\ConfigServiceProvider\Configurator;

trait SupportsInflectorConfig
{
    public function testItSetsUpAnInflector()
    {
        $config = [
            'di' => [
                'services' => [
                    'example' => [
                        'class' => 'tests\mocks\ExampleClass',
                    ],
                ],
                'inflectors' => [
                    'tests\mocks\ExampleInterface' => [
                        'setValue' => ['test_value'],
                    ],
                ],
            ],
        ];

        Configurator::configure($this->container, $config);

        $this->assertEquals(
            'test_value',
            $this->container->get('example')->getValue()
        );
    }

    public function testItSetsUpAnInflectorUsingCustomInflectorsKey()
    {
        $config = [
            'di' => [
                'services' => [
                    'example' => [
                        'class' => 'tests\mocks\ExampleClass',
                    ],
                ],
            ],
            'inflectors' => [
                'tests\mocks\ExampleInterface' => [
                    'setValue' => ['test_value'],
                ],
            ],
        ];

        Configurator::configure($this->container, $config, ['inflectors_key' => 'inflectors']);

        $this->assertEquals(
            'test_value',
            $this->container->get('example')->getValue()
        );
    }
}
