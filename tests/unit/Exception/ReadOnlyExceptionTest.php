<?php

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use LogicException;
use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\ReadOnlyException;

final class ReadOnlyExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        assertInstanceOf(Exception::class, new ReadOnlyException());
    }

    public function testItIsALogicException()
    {
        assertInstanceOf(LogicException::class, new ReadOnlyException());
    }

    public function testItCanBeCreatedFromThePatterns()
    {
        assertSame(
            '"ClassName" is read only.',
            ReadOnlyException::fromClassName('ClassName')->getMessage()
        );
    }
}
