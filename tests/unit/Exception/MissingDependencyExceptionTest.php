<?php

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use LogicException;
use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\MissingDependencyException;

final class MissingDependencyExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        assertInstanceOf(Exception::class, new MissingDependencyException());
    }

    public function testItIsALogicException()
    {
        assertInstanceOf(LogicException::class, new MissingDependencyException());
    }

    public function testItCanBeCreatedFromPackageName()
    {
        assertSame(
            'The package "foo/bar" is missing. Please run "composer require foo/bar" to install it.',
            MissingDependencyException::fromPackageName('foo/bar')->getMessage()
        );
    }
}
