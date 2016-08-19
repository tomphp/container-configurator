<?php

namespace TomPHP\ConfigServiceProvider;

interface ContainerConfigurator
{
    /**
     * @param Config $config
     * @param string $prefix
     *
     * @return void
     */
    public function addConfig(Config $config, $prefix = 'config');
}
