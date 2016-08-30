<?php

namespace tests\TomPHP\ConfigServiceProvider\Exception;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\ReadOnlyException;

final class ReadOnlyExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\Exception\Exception',
            new ReadOnlyException()
        );
    }

    public function testItIsALogicException()
    {
        $this->assertInstanceOf('LogicException', new ReadOnlyException());
    }

    public function testItCanBeCreatedFromThePatterns()
    {
        $this->assertSame(
            '"ClassName" is read only.',
            ReadOnlyException::fromClassName('ClassName')->getMessage()
        );
    }
}
