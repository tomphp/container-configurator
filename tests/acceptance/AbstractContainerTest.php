<?php

namespace tests\acceptance;

use PHPUnit_Framework_TestCase;
use tests\support\TestFileCreator;
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

        ConfigureContainer::apply()
            ->configFromFiles($this->getTestPath('*'))
            ->to($this->container);

        $this->assertSame('example-value', $this->container->get('config.example-key'));
    }

    public function testItAddToConfigUsingFiles()
    {
        $config = ['keyB' => 'valueB'];

        $this->createJSONConfigFile('config.json', $config);

        ConfigureContainer::apply()
            ->configFromArray(['keyA' => 'valueA', 'keyB' => 'valueX'])
            ->configFromFiles($this->getTestPath('*'))
            ->to($this->container);

        $this->assertSame('valueA', $this->container->get('config.keyA'));
        $this->assertSame('valueB', $this->container->get('config.keyB'));
    }
}
