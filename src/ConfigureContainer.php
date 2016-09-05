<?php

namespace TomPHP\ConfigServiceProvider;

final class ConfigureContainer
{
    /**
     * @api
     *
     * @param object $container
     * @param array  $patterns
     * @param array  $settings
     *
     * @return void
     */
    public static function fromFiles($container, array $patterns, $settings = [])
    {
        $settings = self::prepareSettings($settings);
        $appConfig = ApplicationConfig::fromFiles($patterns, $settings['config_separator']);
        self::configureContainer($container, $appConfig, $settings);
    }

    /**
     * @api
     *
     * @param object $container
     * @param array  $config
     * @param array  $settings
     *
     * @return void
     */
    public static function fromArray($container, array $config, $settings = [])
    {
        $settings = self::prepareSettings($settings);
        $appConfig = new ApplicationConfig($config, $settings['config_separator']);
        self::configureContainer($container, $appConfig, $settings);
    }

    /**
     * @param array $settings
     *
     * @return array
     */
    private static function prepareSettings(array $settings)
    {
        return array_merge(
            [
                'config_prefix'      => 'config',
                'config_separator'   => '.',
                'services_key'       => 'di.services',
                'inflectors_key'     => 'di.inflectors',
                'singleton_services' => false,
            ],
            $settings
        );
    }

    private static function configureContainer($container, ApplicationConfig $appConfig, array $settings)
    {
        $factory = new ConfiguratorFactory([
            'League\Container\Container' => 'TomPHP\ConfigServiceProvider\League\LeagueContainerAdapter',
            'Pimple\Container'           => 'TomPHP\ConfigServiceProvider\Pimple\PimpleContainerAdapter',
        ]);

        $configurator = $factory->create($container);

        $configurator->addApplicationConfig($appConfig, $settings['config_prefix']);

        if (isset($appConfig[$settings['services_key']])) {
            $configurator->addServiceConfig(
                new ServiceConfig($appConfig[$settings['services_key']], $settings['singleton_services'])
            );
        }

        if (isset($appConfig[$settings['inflectors_key']])) {
            $configurator->addInflectorConfig(new InflectorConfig($appConfig[$settings['inflectors_key']]));
        }
    }
}
