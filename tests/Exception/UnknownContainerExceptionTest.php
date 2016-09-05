<?php

namespace tests\TomPHP\ConfigServiceProvider\Exception;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\UnknownContainerException;

final class UnknownContainerExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\Exception\Exception',
            new UnknownContainerException()
        );
    }

    public function testItIsADomainException()
    {
        $this->assertInstanceOf('LogicException', new UnknownContainerException());
    }

    public function testItCanBeCreatedFromFileExtension()
    {
        $exception = UnknownContainerException::fromContainerName('example-container', ['container-a', 'container-b']);

        $this->assertSame(
            'Container example-container is unknown; known containers are ["container-a", "container-b"].',
            $exception->getMessage()
        );
    }
}
