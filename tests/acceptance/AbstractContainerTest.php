<?php

namespace tests\acceptance;

use PHPUnit_Framework_TestCase;

abstract class AbstractContainerTest extends PHPUnit_Framework_TestCase
{
    use SupportsApplicationConfig;
    use SupportsServiceConfig;
}
