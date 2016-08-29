<?php

namespace tests\TomPHP\ConfigServiceProvider\Exception;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\InvalidConfigException;

final class InvalidConfigExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\Exception\Exception',
            new InvalidConfigException()
        );
    }

    public function testItIsALogicException()
    {
        $this->assertInstanceOf('LogicException', new InvalidConfigException());
    }

    public function testItCanBeCreatedFromTheFileName()
    {
        $this->assertEquals(
            '"example.cfg" does not return a PHP array.',
            InvalidConfigException::fromPHPFileError('example.cfg')->getMessage()
        );
    }

    public function testItCanBeCreatedWithAJSONFileError()
    {
        $this->assertEquals(
            'Invalid JSON in "example.json": JSON Error Message',
            InvalidConfigException::fromJSONFileError('example.json', 'JSON Error Message')->getMessage()
        );
    }
}
