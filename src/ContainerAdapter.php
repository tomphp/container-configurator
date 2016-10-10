<?php

namespace TomPHP\ContainerConfigurator;

use InvalidArgumentException;

/**
 * @api
 */
interface ContainerAdapter
{
    /**
     * @param object $container
     *
     * @return void
     */
    public function setContainer($container);

    /**
     * @param ApplicationConfig $config
     * @param string            $prefix
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function addApplicationConfig(ApplicationConfig $config, $prefix = 'config');

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
