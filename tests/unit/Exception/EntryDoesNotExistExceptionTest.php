<?php

namespace tests\unit\TomPHP\ConfigServiceProvider\Exception;

use DomainException;
use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\EntryDoesNotExistException;
use TomPHP\ConfigServiceProvider\Exception\Exception;

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
