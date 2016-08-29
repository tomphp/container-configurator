<?php

namespace tests\TomPHP\ConfigServiceProvider\Exception;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\InvalidConfigException;

final class InvalidConfigExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testItImplementsTheBaseExceptionType()
    {
        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\Exception\Exception',
            new InvalidConfigException()
        );
    }

}
