<?php

namespace tests\TomPHP\ConfigServiceProvider;

use League\Container\Container;
use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\ConfigServiceProvider;
use Prophecy\Argument;

final class ConfigServiceProviderTest extends PHPUnit_Framework_TestCase
{
    use TestFileCreator;

    /**
     * @var Container
     */
    private $container;

    protected function setUp()
    {
        $this->container = new Container();

        $this->subProvider = $this->prophesize('TomPHP\ConfigServiceProvider\ConfigurableServiceProvider');

        $this->subProvider->configure(Argument::any())->willReturn();
        $this->subProvider->provides()->willReturn([]);
        $this->subProvider->setContainer(Argument::any())->willReturn();
        $this->subProvider->register()->willReturn();
    }

    protected function tearDown()
    {
        $this->deleteTestFiles();
    }

    public function testItProvidesConfigValuesViaTheDI()
    {
        $config = [
            'test_setting' => 'test value'
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
            'test_setting' => 'test value'
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
            'test_setting' => 'test value'
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
                    'test_setting' => 'test value'
                ]
            ]
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
                    'test_setting' => 'test value'
                ]
            ]
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
                'test_setting' => 'test value'
            ]
        ];

        $this->container->addServiceProvider(new ConfigServiceProvider($config, 'config', '/'));

        $this->assertEquals(
            'test value',
            $this->container->get('config/test_group/test_setting')
        );
    }

    /**
     * @group sub_providers
     */
    public function testItConfiguresASubProvider()
    {
        $config = [
            'sub_provider' => ['key' => 'config'],
        ];

        new ConfigServiceProvider($config, 'config', '.', [
            'sub_provider' => $this->subProvider->reveal(),
        ]);

        $this->subProvider->configure(['key' => 'config'])->shouldHaveBeenCalled();
    }

    /**
     * @group sub_providers
     */
    public function testItSkipsConfiguringASubProvderWithNoConfig()
    {
        new ConfigServiceProvider([], 'config', '.', [
            'sub_provider' => $this->subProvider->reveal(),
        ]);

        $this->subProvider->configure(Argument::any())->shouldNotHaveBeenCalled();
    }

    /**
     * @group sub_providers
     */
    public function testItMergesTheSubProvidersServiceList()
    {
        $this->subProvider->provides()->willReturn(['b']);

        $provider = new ConfigServiceProvider(
            ['sub_provider' => [], 'a' => 1],
            'config',
            '.',
            [ 'sub_provider' => $this->subProvider->reveal() ]
        );

        $this->assertEquals(['config.sub_provider', 'config.a', 'b'], $provider->provides());
    }

    /**
     * @group sub_providers
     */
    public function testItRegistersSubProviders()
    {
        $this->container->addServiceProvider(new ConfigServiceProvider(
            ['sub_provider' => []],
            'config',
            '.',
            [ 'sub_provider' => $this->subProvider->reveal() ]
        ));

        $this->container->get('config.sub_provider');

        $this->subProvider->setContainer($this->container)->shouldHaveBeenCalled();
        $this->subProvider->register()->shouldHaveBeenCalled();
    }

    /**
     * @group sub_providers
     */
    public function testBootableSubProvidersAreBooted()
    {
        $this->subProvider = $this->prophesize('tests\mocks\BootableConfigurableServiceProvider');

        $this->subProvider->configure(Argument::any())->willReturn();
        $this->subProvider->provides()->willReturn([]);
        $this->subProvider->setContainer(Argument::any())->willReturn();
        $this->subProvider->register()->willReturn();
        $this->subProvider->boot()->willReturn();

        $this->container->addServiceProvider(new ConfigServiceProvider(
            ['sub_provider' => []],
            'config',
            '.',
            [ 'sub_provider' => $this->subProvider->reveal() ]
        ));

        $this->container->get('config.sub_provider');

        $this->subProvider->setContainer($this->container)->shouldHaveBeenCalled();
        $this->subProvider->boot()->shouldHaveBeenCalled();
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
                    'setValue' => ['config.test_key']
                ]
            ]
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
                    'setValue' => ['settings/test_key']
                ]
            ]
        ];

        $this->container->addServiceProvider(
            ConfigServiceProvider::fromConfig($config, [
                'prefix' => 'settings',
                'separator' => '/'
            ])
        );

        $this->container->add('example', 'tests\mocks\ExampleClass');

        $this->assertEquals(
            'test value',
            $this->container->get('example')->getValue()
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
                    'setValue' => ['config.test_key']
                ]
            ]
        ];

        $this->createPHPConfigFile('config.php', $config);

        $this->container->addServiceProvider(ConfigServiceProvider::fromFiles([
            $this->getTestPath('*')
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
                    'setValue' => ['settings/test_key']
                ]
            ]
        ];

        $this->createPHPConfigFile('config.php', $config);

        $this->container->addServiceProvider(ConfigServiceProvider::fromFiles(
            [ $this->getTestPath('*') ],
            [
                'prefix' => 'settings',
                'separator' => '/'
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
        $config2 = ['b' => 2, 'c' => 3];

        $this->createPHPConfigFile('config1.php', $config1);
        $this->createPHPConfigFile('config2.php', $config2);

        $this->container->addServiceProvider(ConfigServiceProvider::fromFiles(
            [ $this->getTestPath('*') ]
        ));

        $this->assertEquals(1, $this->container->get('config.a'));
        $this->assertEquals(2, $this->container->get('config.b'));
        $this->assertEquals(3, $this->container->get('config.c'));
    }

    private function createPHPConfigFile($filename, array $config)
    {
        $code = '<?php return ' . var_export($config, true) . ';';

        $this->createTestFile($filename, $code);
    }
}
