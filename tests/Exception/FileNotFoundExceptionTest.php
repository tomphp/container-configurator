<?php

namespace tests\TomPHP\ConfigServiceProvider\Exception;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\FileNotFoundException;

final class FileNotFoundExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItIsAnInstanceOfThePackagesRuntimeException()
    {
        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\Exception\RuntimeException',
            new FileNotFoundException()
        );
    }
}
