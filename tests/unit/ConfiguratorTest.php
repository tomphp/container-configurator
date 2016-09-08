<?php

namespace tests\unit\TomPHP\ConfigServiceProvider;

use PHPUnit_Framework_TestCase;
use tests\support\TestFileCreator;
use TomPHP\ConfigServiceProvider\Configurator;
use TomPHP\ConfigServiceProvider\Exception\NoMatchingFilesException;
use TomPHP\ConfigServiceProvider\Exception\UnknownSettingException;

final class ConfiguratorTest extends PHPUnit_Framework_TestCase
{
    use TestFileCreator;

    public function testItThrowsAnExceptionWhenNoFilesAreNotFound()
    {
        $this->setExpectedException(NoMatchingFilesException::class);

        Configurator::apply()->configFromFiles($this->getTestPath('config.php'));
    }

    public function testItThrowsWhenAnUnknownSettingIsSet()
    {
        $this->setExpectedException(UnknownSettingException::class);

        Configurator::apply()->withSetting('unknown_setting', 'value');
    }
}
