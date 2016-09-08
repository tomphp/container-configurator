<?php

namespace tests\unit\TomPHP\ConfigServiceProvider\Exception;

use LogicException;
use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\Exception;
use TomPHP\ConfigServiceProvider\Exception\FileNotFoundException;

final class FileNotFoundExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        $this->assertInstanceOf(Exception::class, new FileNotFoundException());
    }

    public function testItIsALogicException()
    {
        $this->assertInstanceOf(LogicException::class, new FileNotFoundException());
    }

    public function testItCanBeCreatedFromTheFileName()
    {
        $this->assertSame(
            '"example.cfg" does not exist',
            FileNotFoundException::fromFileName('example.cfg')->getMessage()
        );
    }
}
