<?php

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use LogicException;
use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\NotContainerAdapterException;

final class NotContainerAdapterExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        assertInstanceOf(Exception::class, new NotContainerAdapterException());
    }

    public function testItIsALogicException()
    {
        assertInstanceOf(LogicException::class, new NotContainerAdapterException());
    }

    public function testItCanBeCreatedFromThePatterns()
    {
        assertSame(
            'Class "Foo" is not a container adapter.',
            NotContainerAdapterException::fromClassName('Foo')->getMessage()
        );
    }
}
