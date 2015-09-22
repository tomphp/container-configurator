<?php

namespace tests\TomPHP\ConfigServiceProvider\Exception;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\InvalidConfigException;

final class InvalidConfigExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItIsAnInstanceOfThePackagesRuntimeException()
    {
        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\Exception\RuntimeException',
            new InvalidConfigException()
        );
    }
}
