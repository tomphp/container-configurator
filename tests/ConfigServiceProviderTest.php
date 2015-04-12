<?php

namespace tests\TomPHP\ConfigServiceProvider;

use League\Container\Container;
use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\ConfigServiceProvider;

class ConfigServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    private $container;

    protected function setUp()
    {
        $this->container = new Container();
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

    public function testItCanUseACustomSeperator()
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
}
