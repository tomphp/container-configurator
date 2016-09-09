<?php

namespace tests\unit\TomPHP\ConfigServiceProvider\Exception;

use LogicException;
use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\Exception;
use TomPHP\ConfigServiceProvider\Exception\NoMatchingFilesException;

final class NoMatchingFilesExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        $this->assertInstanceOf(Exception::class, new NoMatchingFilesException());
    }

    public function testItIsALogicException()
    {
        $this->assertInstanceOf(LogicException::class, new NoMatchingFilesException());
    }

    public function testItCanBeCreatedFromThePattern()
    {
        $this->assertSame(
            'No files found matching pattern: "*.json".',
            NoMatchingFilesException::fromPattern('*.json')->getMessage()
        );
    }
}
