<?php

namespace TomPHP\ConfigServiceProvider;

interface ContainerConfigurator
{
    /**
     * @param object            $container
     * @param ApplicationConfig $config
     * @param string            $prefix
     *
     * @return void
     */
    public function addApplicationConfig($container, ApplicationConfig $config, $prefix = 'config');

    /**
     * @param object        $container
     * @param ServiceConfig $config
     *
     * @return void
     */
    public function addServiceConfig($container, ServiceConfig $config);

    /**
     * @param object          $container
     * @param InflectorConfig $config
     *
     * @return void
     */
    public function addInflectorConfig($container, InflectorConfig $config);
}
