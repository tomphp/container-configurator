<?php

namespace tests\acceptance;

final class PimpleContainerAdapterTest extends AbstractContainerAdapterTest
{
    use DoesNotSupportInflectors;

    protected function setUp()
    {
        $this->container = new PimpleContainerWrapper();
    }
}
