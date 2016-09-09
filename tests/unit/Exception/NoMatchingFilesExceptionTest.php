<?php

namespace tests\unit\TomPHP\ConfigServiceProvider\Exception;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\NoMatchingFilesException;

final class NoMatchingFilesExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\Exception\Exception',
            new NoMatchingFilesException()
        );
    }

    public function testItIsALogicException()
    {
        $this->assertInstanceOf('LogicException', new NoMatchingFilesException());
    }

    public function testItCanBeCreatedFromThePattern()
    {
        $this->assertSame(
            'No files found matching pattern: "*.json".',
            NoMatchingFilesException::fromPattern('*.json')->getMessage()
        );
    }
}
