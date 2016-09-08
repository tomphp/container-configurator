<?php

namespace tests\unit\TomPHP\ConfigServiceProvider\Exception;

use LogicException;
use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\Exception;
use TomPHP\ConfigServiceProvider\Exception\UnsupportedFeatureException;

final class UnsupportedFeatureExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItIsAnInstanceOfTheBaseException()
    {
        $this->assertInstanceOf(Exception::class, new UnsupportedFeatureException());
    }

    public function testItIsALogicException()
    {
        $this->assertInstanceOf(LogicException::class, new UnsupportedFeatureException());
    }

    public function testItCanBeCreatedForInflectors()
    {
        $this->assertEquals(
            'container-name does not support inflectors.',
            UnsupportedFeatureException::forInflectors('container-name')->getMessage()
        );
    }
}
