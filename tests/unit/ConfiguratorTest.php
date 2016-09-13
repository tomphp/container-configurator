<?php

namespace tests\unit\TomPHP\ContainerConfigurator;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
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
}
