<?php

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use DomainException;
use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\Exception\EntryDoesNotExistException;
use TomPHP\ContainerConfigurator\Exception\Exception;

final class EntryDoesNotExistExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        $this->assertInstanceOf(Exception::class, new EntryDoesNotExistException());
    }

    public function testItIsADomainException()
    {
        $this->assertInstanceOf(DomainException::class, new EntryDoesNotExistException());
    }

    public function testItCanBeCreatedFromTheKey()
    {
        $this->assertSame(
            'No entry found for "example-key".',
            EntryDoesNotExistException::fromKey('example-key')->getMessage()
        );
    }
}
