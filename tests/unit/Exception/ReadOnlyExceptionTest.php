<?php

namespace tests\unit\TomPHP\ConfigServiceProvider\Exception;

use LogicException;
use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\Exception;
use TomPHP\ConfigServiceProvider\Exception\ReadOnlyException;

final class ReadOnlyExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        $this->assertInstanceOf(Exception::class, new ReadOnlyException());
    }

    public function testItIsALogicException()
    {
        $this->assertInstanceOf(LogicException::class, new ReadOnlyException());
    }

    public function testItCanBeCreatedFromThePatterns()
    {
        $this->assertSame(
            '"ClassName" is read only.',
            ReadOnlyException::fromClassName('ClassName')->getMessage()
        );
    }
}
