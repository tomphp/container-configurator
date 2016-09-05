<?php

namespace tests\unit\TomPHP\ConfigServiceProvider\Exception;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\UnknownSettingException;

final class UnknownSettingExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\Exception\Exception',
            new UnknownSettingException()
        );
    }

    public function testItIsADomainException()
    {
        $this->assertInstanceOf('DomainException', new UnknownSettingException());
    }

    public function testItCanBeCreatedFromFileExtension()
    {
        $exception = UnknownSettingException::fromSetting('unknown', ['setting_a', 'setting_b']);

        $this->assertSame(
            'Setting "unknown" is unknown; valid settings are ["setting_a", "setting_b"].',
            $exception->getMessage()
        );
    }
}
