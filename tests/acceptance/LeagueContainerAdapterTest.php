<?php

namespace tests\acceptance;

use League\Container\Container;

final class LeagueContainerAdapterTest extends AbstractContainerAdapterTest
{
    use SupportsInflectorConfig;

    protected function setUp()
    {
        $this->container = new Container();
    }
}
