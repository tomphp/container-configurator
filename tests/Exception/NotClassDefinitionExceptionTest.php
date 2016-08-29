<?php

namespace tests\TomPHP\ConfigServiceProvider\Exception;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\NotClassDefinitionException;

final class NotClassDefinitionExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\Exception\Exception',
            new NotClassDefinitionException()
        );
    }

    public function testItIsALogicException()
    {
        $this->assertInstanceOf('LogicException', new NotClassDefinitionException());
    }

    public function testItCanBeCreatedFromThePatterns()
    {
        $this->assertEquals(
            'Service configuration for "example-service" did not create a class definition.',
            NotClassDefinitionException::fromServiceName('example-service')->getMessage()
        );
    }
}
