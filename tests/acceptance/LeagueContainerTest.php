<?php

namespace tests\acceptance;

use League\Container\Container;

final class LeagueContainerTest extends AbstractContainerTest
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
