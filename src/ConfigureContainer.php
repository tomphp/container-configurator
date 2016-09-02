<?php

namespace TomPHP\ConfigServiceProvider;

final class ConfigureContainer
{
    /**
     * @api
     *
     * @param object $container
     * @param array  $config
     *
     * @return void
     */
    public static function fromArray($container, array $config, $settings = [])
    {
        $settings = array_merge(
            [
                'config_prefix'    => 'config',
                'config_separator' => '.',
                'services_key'     => 'di.services',
                'inflectors_key'   => 'di.inflectors',
            ],
            $settings
        );

        $factory = new ConfiguratorFactory([
            'League\Container\Container' => 'TomPHP\ConfigServiceProvider\League\Configurator',
            'Pimple\Container'           => 'TomPHP\ConfigServiceProvider\Pimple\Configurator',
        ]);

        $configurator = $factory->create($container);

        $appConfig = new ApplicationConfig($config, $settings['config_separator']);
        $configurator->addApplicationConfig($container, $appConfig, $settings['config_prefix']);

        if (isset($appConfig[$settings['services_key']])) {
            $configurator->addServiceConfig($container, new ServiceConfig($appConfig[$settings['services_key']]));
        }

        if (isset($appConfig[$settings['inflectors_key']])) {
            $configurator->addInflectorConfig($container, new InflectorConfig($appConfig[$settings['inflectors_key']]));
        }
    }
}
