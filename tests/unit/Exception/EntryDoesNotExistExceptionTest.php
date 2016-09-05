<?php

namespace tests\unit\TomPHP\ConfigServiceProvider\Exception;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\EntryDoesNotExistException;

final class EntryDoesNotExistExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\Exception\Exception',
            new EntryDoesNotExistException()
        );
    }

    public function testItIsADomainException()
    {
        $this->assertInstanceOf('DomainException', new EntryDoesNotExistException());
    }

    public function testItCanBeCreatedFromTheKey()
    {
        $this->assertSame(
            'No entry found for "example-key".',
            EntryDoesNotExistException::fromKey('example-key')->getMessage()
        );
    }
}
