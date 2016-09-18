<?php

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use LogicException;
use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\UnsupportedFeatureException;

final class UnsupportedFeatureExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItIsAnInstanceOfTheBaseException()
    {
        assertInstanceOf(Exception::class, new UnsupportedFeatureException());
    }

    public function testItIsALogicException()
    {
        assertInstanceOf(LogicException::class, new UnsupportedFeatureException());
    }

    public function testItCanBeCreatedForInflectors()
    {
        assertEquals(
            'container-name does not support inflectors.',
            UnsupportedFeatureException::forInflectors('container-name')->getMessage()
        );
    }
}
