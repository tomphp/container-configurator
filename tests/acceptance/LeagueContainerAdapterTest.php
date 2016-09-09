<?php

namespace tests\acceptance;

use League\Container\Container;

final class LeagueContainerAdapterTest extends AbstractContainerAdapterTest
{
    use SupportsInflectorConfig;

    /**
     * @var Container
     */
    protected $container;

    protected function setUp()
    {
        $this->container = new Container();
    }
}
