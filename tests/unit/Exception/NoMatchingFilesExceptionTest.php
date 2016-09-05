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

    public function testItCanBeCreatedFromThePatterns()
    {
        $this->assertSame(
            'No files found matching patterns: ["*.json", "*.php"].',
            NoMatchingFilesException::fromPatterns(['*.json', '*.php'])->getMessage()
        );
    }
}
