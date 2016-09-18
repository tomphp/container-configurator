<?php

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use LogicException;
use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\NotClassDefinitionException;

final class NotClassDefinitionExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        assertInstanceOf(Exception::class, new NotClassDefinitionException());
    }

    public function testItIsALogicException()
    {
        assertInstanceOf(LogicException::class, new NotClassDefinitionException());
    }

    public function testItCanBeCreatedFromThePatterns()
    {
        assertSame(
            'Service configuration for "example-service" did not create a class definition.',
            NotClassDefinitionException::fromServiceName('example-service')->getMessage()
        );
    }
}
