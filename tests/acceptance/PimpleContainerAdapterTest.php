<?php

namespace tests\acceptance;

use Pimple\Container;

final class PimpleContainerAdapterTest extends AbstractContainerAdapterTest
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
