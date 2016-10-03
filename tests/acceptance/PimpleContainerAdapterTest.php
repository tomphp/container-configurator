<?php

namespace tests\acceptance;

final class PimpleContainerAdapterTest extends AbstractContainerAdapterTest
{
    protected function setUp()
    {
        $this->container = new PimpleContainerWrapper();
    }
}
