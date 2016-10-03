<?php

namespace tests\acceptance;

use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase;
use tests\support\TestFileCreator;
use TomPHP\ContainerConfigurator\Configurator;

abstract class AbstractContainerAdapterTest extends PHPUnit_Framework_TestCase
{
    use SupportsApplicationConfig;
    use SupportsServiceConfig;
    use SupportsInflectorConfig;
    use TestFileCreator;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function testItCanBeConfiguredFromAFile()
    {
        $config = ['example-key' => 'example-value'];

        $this->createJSONConfigFile('config.json', $config);

        Configurator::apply()
            ->configFromFile($this->getTestPath('config.json'))
            ->to($this->container);

        assertSame('example-value', $this->container->get('config.example-key'));
    }

    public function testItCanBeConfiguredFromFiles()
    {
        $config = ['example-key' => 'example-value'];

        $this->createJSONConfigFile('config.json', $config);

        Configurator::apply()
            ->configFromFiles($this->getTestPath('*'))
            ->to($this->container);

        assertSame('example-value', $this->container->get('config.example-key'));
    }

    public function testItAddToConfigUsingFiles()
    {
        $config = ['keyB' => 'valueB'];

        $this->createJSONConfigFile('config.json', $config);

        Configurator::apply()
            ->configFromArray(['keyA' => 'valueA', 'keyB' => 'valueX'])
            ->configFromFiles($this->getTestPath('*'))
            ->to($this->container);

        assertSame('valueA', $this->container->get('config.keyA'));
        assertSame('valueB', $this->container->get('config.keyB'));
    }
}
