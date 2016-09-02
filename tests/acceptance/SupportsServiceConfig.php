<?php

namespace tests\acceptance;

use TomPHP\ConfigServiceProvider\ConfigureContainer;

trait SupportsServiceConfig
{
    public function testItAddsServicesToTheContainer()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => 'tests\mocks\ExampleClass',
                    ],
                ],
            ],
        ];

        ConfigureContainer::fromArray($this->container, $config);

        $this->assertInstanceOf(
            'tests\mocks\ExampleClass',
            $this->container->get('example_class')
        );
    }

    public function testItAddsServicesToTheContainerForADifferentConfigKey()
    {
        $config = [
            'di' => [
                'example_class' => [
                    'class' => 'tests\mocks\ExampleClass',
                ],
            ],
        ];

        ConfigureContainer::fromArray($this->container, $config, ['services_key' => 'di']);

        $this->assertInstanceOf(
            'tests\mocks\ExampleClass',
            $this->container->get('example_class')
        );
    }

    public function testItCreatesUniqueServiceInstancesByDefault()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'     => 'tests\mocks\ExampleClass',
                        'singleton' => false,
                    ],
                ],
            ],
        ];

        ConfigureContainer::fromArray($this->container, $config);

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertNotSame($instance1, $instance2);
    }

    public function testItCanCreateSingletonServiceInstances()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'     => 'tests\mocks\ExampleClass',
                        'singleton' => true,
                    ],
                ],
            ],
        ];

        ConfigureContainer::fromArray($this->container, $config);

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertSame($instance1, $instance2);
    }

    public function testItCanCreatesSingletonServiceInstancesByDefault()
    {
        $this->markTestIncomplete();

        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'     => 'tests\mocks\ExampleClass',
                    ],
                ],
            ],
        ];

        ConfigureContainer::fromArray($this->container, $config, ['singleton_services' => true]);

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertSame($instance1, $instance2);
    }

    public function testItCanCreateUniqueServiceInstancesWhenSingletonIsDefault()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'     => 'tests\mocks\ExampleClass',
                        'singleton' => false,
                    ],
                ],
            ],
        ];

        ConfigureContainer::fromArray($this->container, $config, ['singleton_services' => true]);

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertNotSame($instance1, $instance2);
    }

    public function testItAddsConstructorArguments()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => 'tests\mocks\ExampleClassWithArgs',
                        'arguments' => [
                            'arg1',
                            'arg2',
                        ],
                    ],
                ],
            ],
        ];

        ConfigureContainer::fromArray($this->container, $config);

        $instance = $this->container->get('example_class');

        $this->assertEquals(['arg1', 'arg2'], $instance->getConstructorArgs());
    }

    public function testItResolvesConstructorArgumentsIfTheyAreServiceNames()
    {
        $config = [
            'arg1' => 'value1',
            'arg2' => 'value2',
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => 'tests\mocks\ExampleClassWithArgs',
                        'arguments' => [
                            'config.arg1',
                            'config.arg2',
                        ],
                    ],
                ],
            ],
        ];

        ConfigureContainer::fromArray($this->container, $config);

        $instance = $this->container->get('example_class');

        $this->assertEquals(['value1', 'value2'], $instance->getConstructorArgs());
    }

    public function testItUsesTheStringIfConstructorArgumentsAreClassNames()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => 'tests\mocks\ExampleClassWithArgs',
                        'arguments' => [
                            'tests\mocks\ExampleClass',
                            'arg2',
                        ],
                    ],
                ],
            ],
        ];

        ConfigureContainer::fromArray($this->container, $config);

        $instance = $this->container->get('example_class');

        $this->assertEquals(['tests\mocks\ExampleClass', 'arg2'], $instance->getConstructorArgs());
    }

    public function testItCallsSetterMethods()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => 'tests\mocks\ExampleClass',
                        'methods' => [
                            'setValue' => ['the value'],
                        ],
                    ],
                ],
            ],
        ];

        ConfigureContainer::fromArray($this->container, $config);

        $instance = $this->container->get('example_class');

        $this->assertEquals('the value', $instance->getValue());
    }

    public function testItResolvesSetterMethodArgumentsIfTheyAreServiceNames()
    {
        $config = [
            'arg' => 'value',
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => 'tests\mocks\ExampleClass',
                        'methods' => [
                            'setValue' => ['config.arg'],
                        ],
                    ],
                ],
            ],
        ];

        ConfigureContainer::fromArray($this->container, $config);

        $instance = $this->container->get('example_class');

        $this->assertEquals('value', $instance->getValue());
    }

    public function testItUsesTheStringIffSetterMethodArgumentsAreClassNames()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => 'tests\mocks\ExampleClass',
                        'methods' => [
                            'setValue' => ['tests\mocks\ExampleClass'],
                        ],
                    ],
                ],
            ],
        ];

        ConfigureContainer::fromArray($this->container, $config);

        $instance = $this->container->get('example_class');

        $this->assertSame('tests\mocks\ExampleClass', $instance->getValue());
    }
}
