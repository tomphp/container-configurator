<?php

namespace tests\acceptance;

use TomPHP\ConfigServiceProvider\ConfigureContainer;

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

        ConfigureContainer::apply()->configFromArray($config)->to($this->container);

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

        ConfigureContainer::apply()
            ->configFromArray($config)
            ->withSetting('inflectors_key', 'inflectors')
            ->to($this->container);

        $this->assertEquals(
            'test_value',
            $this->container->get('example')->getValue()
        );
    }
}
