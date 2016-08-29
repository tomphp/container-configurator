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

    /**
     * @param ServiceConfig $config
     *
     * @return void
     */
    public function addServiceConfig(ServiceConfig $config);

    /**
     * @param InflectorConfig $config
     *
     * @return void
     */
    public function addInflectorConfig(InflectorConfig $config);
}
