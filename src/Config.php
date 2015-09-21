<?php

namespace TomPHP\ConfigServiceProvider;

use League\Container\ContainerInterface;

final class Config
{
    const INFLECTORS_CONFIG_KEY = 'inflectors';

    /**
     * @api
     */
    public static function addToContainer(ContainerInterface $container, array $config)
    {
        $container->addServiceProvider(new ConfigServiceProvider($config));

        if (array_key_exists(self::INFLECTORS_CONFIG_KEY, $config)) {
            $container->addServiceProvider(
                new InflectorConfigServiceProvider($config[self::INFLECTORS_CONFIG_KEY])
            );
        }
    }
}
