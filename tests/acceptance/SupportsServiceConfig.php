<?php

namespace tests\acceptance;

use tests\mocks\ExampleClass;
use tests\mocks\ExampleClassWithArgs;
use tests\mocks\ExampleFactory;
use TomPHP\ContainerConfigurator\Configurator;

trait SupportsServiceConfig
{
    public function testItAddsServicesToTheContainer()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => ExampleClass::class,
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        assertInstanceOf(ExampleClass::class, $this->container->get('example_class'));
    }

    public function testItAddsServicesToTheContainerForADifferentConfigKey()
    {
        $config = [
            'di' => [
                'example_class' => [
                    'class' => ExampleClass::class,
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->withSetting(Configurator::SETTING_SERVICES_KEY, 'di')
            ->to($this->container);

        assertInstanceOf(ExampleClass::class, $this->container->get('example_class'));
    }

    public function testItCreatesUniqueServiceInstancesByDefault()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'     => ExampleClass::class,
                        'singleton' => false,
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        assertNotSame($instance1, $instance2);
    }

    public function testItCanCreateSingletonServiceInstances()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'     => ExampleClass::class,
                        'singleton' => true,
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        assertSame($instance1, $instance2);
    }

    public function testItCanCreateSingletonServiceInstancesByDefault()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => ExampleClass::class,
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->withSetting(Configurator::SETTING_DEFAULT_SINGLETON_SERVICES, true)
            ->to($this->container);

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        assertSame($instance1, $instance2);
    }

    public function testItCanCreateUniqueServiceInstancesWhenSingletonIsDefault()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'     => ExampleClass::class,
                        'singleton' => false,
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->withSetting(Configurator::SETTING_DEFAULT_SINGLETON_SERVICES, true)
            ->to($this->container);

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        assertNotSame($instance1, $instance2);
    }

    public function testItAddsConstructorArguments()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => ExampleClassWithArgs::class,
                        'arguments' => [
                            'arg1',
                            'arg2',
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_class');

        assertEquals(['arg1', 'arg2'], $instance->getConstructorArgs());
    }

    public function testItResolvesConstructorArgumentsIfTheyAreServiceNames()
    {
        $config = [
            'arg1' => 'value1',
            'arg2' => 'value2',
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => ExampleClassWithArgs::class,
                        'arguments' => [
                            'config.arg1',
                            'config.arg2',
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_class');

        assertEquals(['value1', 'value2'], $instance->getConstructorArgs());
    }

    public function testItUsesTheStringIfConstructorArgumentsAreClassNames()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => ExampleClassWithArgs::class,
                        'arguments' => [
                            ExampleClass::class,
                            'arg2',
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_class');

        assertEquals([ExampleClass::class, 'arg2'], $instance->getConstructorArgs());
    }

    public function testItCallsSetterMethods()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => ExampleClass::class,
                        'methods' => [
                            'setValue' => ['the value'],
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_class');

        assertEquals('the value', $instance->getValue());
    }

    public function testItResolvesSetterMethodArgumentsIfTheyAreServiceNames()
    {
        $config = [
            'arg' => 'value',
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => ExampleClass::class,
                        'methods' => [
                            'setValue' => ['config.arg'],
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_class');

        assertEquals('value', $instance->getValue());
    }

    public function testItUsesTheStringIffSetterMethodArgumentsAreClassNames()
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => ExampleClass::class,
                        'methods' => [
                            'setValue' => [ExampleClass::class],
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_class');

        assertSame(ExampleClass::class, $instance->getValue());
    }

    public function testIsCreatesAServiceThroughAFactoryClass()
    {
        $config = [
            'class_name' => ExampleClassWithArgs::class,
            'di' => [
                'services' => [
                    'example_service' => [
                        'factory'   => ExampleFactory::class,
                        'arguments' => [
                            'config.class_name',
                            'example_argument',
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_service');

        assertInstanceOf(ExampleClassWithArgs::class, $instance);
        assertSame(['example_argument'], $instance->getConstructorArgs());
    }
}
