<?php

namespace tests\unit\TomPHP\ConfigServiceProvider;

use PHPUnit_Framework_TestCase;
use tests\support\TestFileCreator;
use TomPHP\ConfigServiceProvider\Configurator;

final class ConfiguratorTest extends PHPUnit_Framework_TestCase
{
    use TestFileCreator;

    public function testItThrowsAnExceptionWhenNoFilesAreNotFound()
    {
        $this->setExpectedException('TomPHP\ConfigServiceProvider\Exception\NoMatchingFilesException');

        Configurator::apply()->configFromFiles($this->getTestPath('config.php'));
    }

    public function testItThrowsWhenAnUnknownSettingIsSet()
    {
        $this->setExpectedException('TomPHP\ConfigServiceProvider\Exception\UnknownSettingException');

        Configurator::apply()->withSetting('unknown_setting', 'value');
    }
}
