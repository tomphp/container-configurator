<?php

namespace tests\TomPHP\ConfigServiceProvider\Exception;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\UnknownFileTypeException;
use TomPHP\ConfigServiceProvider\Exception\UnsupportedFeatureException;

final class UnsupportedFeatureExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItIsAnInstanceOfTheBaseException()
    {
        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\Exception\Exception',
            new UnsupportedFeatureException()
        );
    }

    public function testItIsALogicException()
    {
        $this->assertInstanceOf('LogicException', new UnsupportedFeatureException());
    }

    public function testItCanBeCreatedForInflectors()
    {
        $this->assertEquals(
            'container-name does not support inflectors.',
            UnsupportedFeatureException::forInflectors('container-name')->getMessage()
        );
    }
}
