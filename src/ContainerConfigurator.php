<?php

namespace TomPHP\ConfigServiceProvider;

interface ContainerConfigurator
{
    /**
     * @param ApplicationConfig $config
     * @param string            $prefix
     *
     * @return void
     */
    public function addConfig(ApplicationConfig $config, $prefix = 'config');
}
