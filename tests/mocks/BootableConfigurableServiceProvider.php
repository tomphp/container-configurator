<?php

namespace tests\mocks;

use League\Container\ServiceProvider\BootableServiceProviderInterface;
use TomPHP\ConfigServiceProvider\ConfigurableServiceProvider;

interface BootableConfigurableServiceProvider extends
    BootableServiceProviderInterface,
    ConfigurableServiceProvider
{
}
