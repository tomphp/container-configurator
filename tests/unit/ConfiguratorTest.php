<?php

namespace tests\unit\TomPHP\ConfigServiceProvider;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use tests\support\TestFileCreator;
use TomPHP\ConfigServiceProvider\Configurator;
use TomPHP\ConfigServiceProvider\Exception\NoMatchingFilesException;
use TomPHP\ConfigServiceProvider\Exception\UnknownSettingException;

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
