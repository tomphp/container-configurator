<?php

namespace TomPHP\ConfigServiceProvider;

use League\Container\ServiceProvider\ServiceProviderInterface;

interface ConfigurableServiceProvider extends ServiceProviderInterface
{
    /**
     * @param array $config
     *
     * @return void
     */
    public function configure(array $config);
}
