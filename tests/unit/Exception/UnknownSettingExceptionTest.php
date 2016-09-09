<?php

namespace tests\unit\TomPHP\ConfigServiceProvider\Exception;

use DomainException;
use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\Exception;
use TomPHP\ConfigServiceProvider\Exception\UnknownSettingException;

final class UnknownSettingExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        $this->assertInstanceOf(Exception::class, new UnknownSettingException());
    }

    public function testItIsADomainException()
    {
        $this->assertInstanceOf(DomainException::class, new UnknownSettingException());
    }

    public function testItCanBeCreatedFromSetting()
    {
        $exception = UnknownSettingException::fromSetting('unknown', ['setting_a', 'setting_b']);

        $this->assertSame(
            'Setting "unknown" is unknown; valid settings are ["setting_a", "setting_b"].',
            $exception->getMessage()
        );
    }
}
