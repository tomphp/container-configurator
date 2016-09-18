<?php

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use DomainException;
use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\UnknownSettingException;

final class UnknownSettingExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        assertInstanceOf(Exception::class, new UnknownSettingException());
    }

    public function testItIsADomainException()
    {
        assertInstanceOf(DomainException::class, new UnknownSettingException());
    }

    public function testItCanBeCreatedFromSetting()
    {
        $exception = UnknownSettingException::fromSetting('unknown', ['setting_a', 'setting_b']);

        assertSame(
            'Setting "unknown" is unknown; valid settings are ["setting_a", "setting_b"].',
            $exception->getMessage()
        );
    }
}
