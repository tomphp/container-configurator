<?php

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use LogicException;
use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\NoMatchingFilesException;

final class NoMatchingFilesExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        assertInstanceOf(Exception::class, new NoMatchingFilesException());
    }

    public function testItIsALogicException()
    {
        assertInstanceOf(LogicException::class, new NoMatchingFilesException());
    }

    public function testItCanBeCreatedFromThePattern()
    {
        assertSame(
            'No files found matching pattern: "*.json".',
            NoMatchingFilesException::fromPattern('*.json')->getMessage()
        );
    }
}
