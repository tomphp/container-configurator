<?php

namespace tests\acceptance;

use tests\mocks\ExampleClass;
use tests\mocks\ExampleInterface;
use TomPHP\ContainerConfigurator\Configurator;

trait SupportsInflectorConfig
{
    public function testItSetsUpAnInflector()
    {
        $config = [
            'di' => [
                'services' => [
                    'example' => [
                        'class' => ExampleClass::class,
                    ],
                ],
                'inflectors' => [
                    ExampleInterface::class => [
                        'setValue' => ['test_value'],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        assertEquals(
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
                        'class' => ExampleClass::class,
                    ],
                ],
            ],
            'inflectors' => [
                ExampleInterface::class => [
                    'setValue' => ['test_value'],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->withSetting(Configurator::SETTING_INFLECTORS_KEY, 'inflectors')
            ->to($this->container);

        assertEquals(
            'test_value',
            $this->container->get('example')->getValue()
        );
    }
}
