<?php

namespace tests\acceptance;

use PHPUnit_Framework_TestCase;
use tests\TomPHP\ConfigServiceProvider\TestFileCreator;
use TomPHP\ConfigServiceProvider\ConfigureContainer;

abstract class AbstractContainerTest extends PHPUnit_Framework_TestCase
{
    use SupportsApplicationConfig;
    use SupportsServiceConfig;
    use TestFileCreator;

    public function testItCanBeConfiguredFromFiles()
    {
        $config = ['example-key' => 'example-value'];

        $this->createJSONConfigFile('config.json', $config);

        ConfigureContainer::fromFiles(
            $this->container,
            [$this->getTestPath('*')],
            ['config_prefix' => 'settings']
        );

        $this->assertSame('example-value', $this->container->get('settings.example-key'));
    }
}
