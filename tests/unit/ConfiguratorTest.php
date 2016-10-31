<?php

namespace tests\unit\TomPHP\ContainerConfigurator;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use Pimple\Container;
use tests\mocks\ExampleContainer;
use tests\mocks\ExampleContainerAdapter;
use tests\mocks\FileReader\CustomFileReader;
use tests\support\TestFileCreator;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\ContainerConfigurator\Exception\NoMatchingFilesException;
use TomPHP\ContainerConfigurator\Exception\UnknownSettingException;

final class ConfiguratorTest extends PHPUnit_Framework_TestCase
{
    use TestFileCreator;

    public function testItThrowsAnExceptionWhenTheFileIsNotFound()
    {
        $this->expectException(InvalidArgumentException::class);

        Configurator::apply()->configFromFile($this->getTestPath('config.php'));
    }

    public function testItThrowsAnExceptionWhenNoFilesAreNotFound()
    {
        $this->expectException(NoMatchingFilesException::class);

        Configurator::apply()->configFromFiles($this->getTestPath('config.php'));
    }

    public function testItThrowsWhenAnUnknownSettingIsSet()
    {
        $this->expectException(UnknownSettingException::class);

        Configurator::apply()->withSetting('unknown_setting', 'value');
    }

    public function testTheContainerIdentifierStringIsAlwaysTheSame()
    {
        assertSame(Configurator::container(), Configurator::container());
    }

    public function testItCanAcceptADifferentFileReader()
    {
        $container = new Container();
        $this->createTestFile('custom.xxx');
        CustomFileReader::reset();

        $configFile = $this->getTestPath('custom.xxx');
        Configurator::apply()
            ->withFileReader('.xxx', CustomFileReader::class)
            ->configFromFile($configFile)
            ->to($container);

        assertSame([$configFile], CustomFileReader::getReads());
    }

    public function testItCanUseDifferentContainerAdapters()
    {
        $container = new ExampleContainer();
        ExampleContainerAdapter::reset();

        Configurator::apply()
            ->withContainerAdapter(ExampleContainer::class, ExampleContainerAdapter::class)
            ->configFromArray([])
            ->to($container);

        assertSame(1, ExampleContainerAdapter::getNumberOfInstances());
    }
}
