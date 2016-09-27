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
        assertInstanceOf(Exception::class, new InvalidConfigException());
    }

    public function testItIsALogicException()
    {
        assertInstanceOf(LogicException::class, new InvalidConfigException());
    }

    public function testItCanBeCreatedFromTheFileName()
    {
        assertSame(
            '"example.cfg" does not return a PHP array.',
            InvalidConfigException::fromPHPFileError('example.cfg')->getMessage()
        );
    }

    public function testItCanBeCreatedWithAJSONFileError()
    {
        assertSame(
            'Invalid JSON in "example.json": JSON Error Message',
            InvalidConfigException::fromJSONFileError('example.json', 'JSON Error Message')->getMessage()
        );
    }

    public function testItCanBeCreatedFromYAMLFileError()
    {
        assertSame(
            'Invalid YAML in "example.yml": YAML Error Message',
            InvalidConfigException::fromYAMLFileError('example.yml', 'YAML Error Message')->getMessage()
        );
    }

    public function testItCanBeCreatedFromNameWhenClassAndFactoryAreSpecified()
    {
        assertSame(
            'Both "class" and "factory" are specified for service "example"; these cannot be used together.',
            InvalidConfigException::fromNameWhenClassAndFactorySpecified('example')->getMessage()
        );
    }

    public function testItCanBeCreatedFromNameWhenClassAndServiceAreSpecified()
    {
        assertSame(
            'Both "class" and "service" are specified for service "example"; these cannot be used together.',
            InvalidConfigException::fromNameWhenClassAndServiceSpecified('example')->getMessage()
        );
    }

    public function testItCanBeCreatedFromNameWhenFactoryAndServiceAreSpecified()
    {
        assertSame(
            'Both "factory" and "service" are specified for service "example"; these cannot be used together.',
            InvalidConfigException::fromNameWhenFactoryAndServiceSpecified('example')->getMessage()
        );
    }
}
