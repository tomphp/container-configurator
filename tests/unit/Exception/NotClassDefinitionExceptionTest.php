<?php

namespace tests\unit\TomPHP\ConfigServiceProvider\Exception;

use LogicException;
use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\Exception;
use TomPHP\ConfigServiceProvider\Exception\NotClassDefinitionException;

final class NotClassDefinitionExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        $this->assertInstanceOf(Exception::class, new NotClassDefinitionException());
    }

    public function testItIsALogicException()
    {
        $this->assertInstanceOf(LogicException::class, new NotClassDefinitionException());
    }

    public function testItCanBeCreatedFromThePatterns()
    {
        $this->assertSame(
            'Service configuration for "example-service" did not create a class definition.',
            NotClassDefinitionException::fromServiceName('example-service')->getMessage()
        );
    }
}
