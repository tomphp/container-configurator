<?php

namespace tests\acceptance;

use Pimple\Container;
use tests\TomPHP\ConfigServiceProvider\Pimple\PimpleContainerWrapper;

final class PimpleContainerTest extends AbstractContainerTest
{
    use DoesNotSupportInflectors;

    /**
     * @var Container
     */
    protected $container;

    protected function setUp()
    {
        $this->container = new PimpleContainerWrapper();
    }
}
