<?php

namespace tests\unit\TomPHP\ConfigServiceProvider;

use League\Container\Container;
use League\Container\ServiceProvider\ServiceProviderInterface;
use PHPUnit_Framework_TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use tests\support\TestFileCreator;
use TomPHP\ConfigServiceProvider\ConfigServiceProvider;
use Prophecy\Argument;

final class ConfigServiceProviderTest extends PHPUnit_Framework_TestCase
{
    use TestFileCreator;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var ServiceProviderInterface|ObjectProphecy
     */
    private $subProvider;

    protected function setUp()
    {
        $this->container = new Container();

        $this->subProvider = $this->prophesize('League\Container\ServiceProvider\ServiceProviderInterface');

        $this->subProvider->provides()->willReturn([]);
        $this->subProvider->setContainer(Argument::any())->willReturn();
        $this->subProvider->register()->willReturn();
    }

    public function testItProvidesConfigValuesViaTheDI()
    {
        $config = [
            'test_setting' => 'test value',
        ];

        $this->container->addServiceProvider(new ConfigServiceProvider($config));

        $this->assertEquals(
            'test value',
            $this->container->get('config.test_setting')
        );
    }

    public function testItCanAUseCustomPrefix()
    {
        $config = [
            'test_setting' => 'test value',
        ];

        $this->container->addServiceProvider(new ConfigServiceProvider($config, 'settings'));

        $this->assertEquals(
            'test value',
            $this->container->get('settings.test_setting')
        );
    }

    public function testItCanUseNoPrefix()
    {
        $config = [
            'test_setting' => 'test value',
        ];

        $this->container->addServiceProvider(new ConfigServiceProvider($config, ''));

        $this->assertEquals(
            'test value',
            $this->container->get('test_setting')
        );
    }

    public function testItExpandsSubGroups()
    {
        $config = [
            'test_group' => [
                'sub_group' => [
                    'test_setting' => 'test value',
                ],
            ],
        ];

        $this->container->addServiceProvider(new ConfigServiceProvider($config));

        $this->assertEquals(
            'test value',
            $this->container->get('config.test_group.sub_group.test_setting')
        );
    }

    public function testItMakesSubGroupsAvailableAsArrays()
    {
        $config = [
            'test_group' => [
                'sub_group' => [
                    'test_setting' => 'test value',
                ],
            ],
        ];

        $this->container->addServiceProvider(new ConfigServiceProvider($config));

        $this->assertEquals(
            ['test_setting' => 'test value'],
            $this->container->get('config.test_group.sub_group')
        );
    }

    public function testItCanUseACustomSeparator()
    {
        $config = [
            'test_group' => [
                'test_setting' => 'test value',
            ],
        ];

        $this->container->addServiceProvider(new ConfigServiceProvider($config, 'config', '/'));

        $this->assertEquals(
            'test value',
            $this->container->get('config/test_group/test_setting')
        );
    }

    /**
     * @group from_config_factory
     */
    public function testItCreatesFromConfigWithDefaultSettings()
    {
        $config = [
            'test_key' => 'test value',

            'inflectors' => [
                'tests\mocks\ExampleInterface' => [
                    'setValue' => ['config.test_key'],
                ],
            ],
        ];

        $this->container->addServiceProvider(ConfigServiceProvider::fromConfig($config));

        $this->container->add('example', 'tests\mocks\ExampleClass');

        $this->assertEquals(
            'test value',
            $this->container->get('example')->getValue()
        );
    }

    /**
     * @group from_config_factory
     */
    public function testItCanOverrideFromConfigDefaults()
    {
        $config = [
            'test_key' => 'test value',

            'inflectors' => [
                'tests\mocks\ExampleInterface' => [
                    'setValue' => ['settings/test_key'],
                ],
            ],
        ];

        $this->container->addServiceProvider(
            ConfigServiceProvider::fromConfig($config, [
                'prefix' => 'settings',
                'separator' => '/',
            ])
        );

        $this->container->add('example', 'tests\mocks\ExampleClass');

        $this->assertEquals(
            'test value',
            $this->container->get('example')->getValue()
        );
    }

    /**
     * @group from_config_factory
     */
    public function testItCanConfigureDI()
    {
        $config = [
            'test_key' => 'test value',

            'di' => [
                'example_class' => [
                    'class' => 'tests\mocks\ExampleClass',
                ],
            ],
        ];

        $this->container->addServiceProvider(ConfigServiceProvider::fromConfig($config));

        $this->assertInstanceOf(
            'tests\mocks\ExampleClass',
            $this->container->get('example_class')
        );
    }

    /**
     * @group from_files_factory
     */
    public function testItCreatesFromParsingFiles()
    {
        $config = [
            'test_key' => 'test value',

            'inflectors' => [
                'tests\mocks\ExampleInterface' => [
                    'setValue' => ['config.test_key'],
                ],
            ],
        ];

        $this->createPHPConfigFile('config.php', $config);

        $this->container->addServiceProvider(ConfigServiceProvider::fromFiles([
            $this->getTestPath('*'),
        ]));

        $this->container->add('example', 'tests\mocks\ExampleClass');

        $this->assertEquals(
            'test value',
            $this->container->get('example')->getValue()
        );
    }

    /**
     * @group from_files_factory
     */
    public function testItCanOverrideFromFilesDefaults()
    {
        $config = [
            'test_key' => 'test value',

            'inflectors' => [
                'tests\mocks\ExampleInterface' => [
                    'setValue' => ['settings/test_key'],
                ],
            ],
        ];

        $this->createPHPConfigFile('config.php', $config);

        $this->container->addServiceProvider(ConfigServiceProvider::fromFiles(
            [ $this->getTestPath('*') ],
            [
                'prefix' => 'settings',
                'separator' => '/',
            ]
        ));

        $this->container->add('example', 'tests\mocks\ExampleClass');

        $this->assertEquals(
            'test value',
            $this->container->get('example')->getValue()
        );
    }

    /**
     * @group from_files_factory
     */
    public function testItMergesConfigsFromFiles()
    {
        $config1 = ['a' => 1, 'b' => 5];
        $config2 = ['b' => 2, 'c' => 7];
        $config3 = ['c' => 3, 'd' => 4];

        $this->createPHPConfigFile('config1.php', $config1);
        $this->createPHPConfigFile('config2.php', $config2);
        $this->createJSONConfigFile('config3.json', $config3);

        $this->container->addServiceProvider(ConfigServiceProvider::fromFiles(
            [ $this->getTestPath('*') ]
        ));

        $this->assertEquals(1, $this->container->get('config.a'));
        $this->assertEquals(2, $this->container->get('config.b'));
        $this->assertEquals(3, $this->container->get('config.c'));
        $this->assertEquals(4, $this->container->get('config.d'));
    }

    /**
     * @group from_files_factory
     */
    public function testItThrowsWhenCreatingFromFilesAndNoConfigFilesAreFound()
    {
        $this->setExpectedException(
            'TomPHP\ConfigServiceProvider\Exception\NoMatchingFilesException'
        );

        $this->container->addServiceProvider(ConfigServiceProvider::fromFiles([
            $this->getTestPath('*'),
        ]));
    }

    /**
     * @link https://github.com/thephpleague/container/issues/106
     */
    public function testFetchingConfigValueWhereValueIsAClassNameReturnsValue()
    {
        $value = 'stdClass';

        $this->container->addServiceProvider(ConfigServiceProvider::fromConfig([
            'foo' => $value,
        ]));

        $this->assertSame($value, $this->container->get('config.foo'));
    }
}
