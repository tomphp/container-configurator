<?php

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use LogicException;
use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;

final class InvalidConfigExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        $this->assertInstanceOf(Exception::class, new InvalidConfigException());
    }

    public function testItIsALogicException()
    {
        $this->assertInstanceOf(LogicException::class, new InvalidConfigException());
    }

    public function testItCanBeCreatedFromTheFileName()
    {
        $this->assertSame(
            '"example.cfg" does not return a PHP array.',
            InvalidConfigException::fromPHPFileError('example.cfg')->getMessage()
        );
    }

    public function testItCanBeCreatedWithAJSONFileError()
    {
        $this->assertSame(
            'Invalid JSON in "example.json": JSON Error Message',
            InvalidConfigException::fromJSONFileError('example.json', 'JSON Error Message')->getMessage()
        );
    }
}
