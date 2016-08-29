<?php

namespace TomPHP\ConfigServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use TomPHP\ConfigServiceProvider\League\AggregateServiceProvider;
use League\Container\ContainerInterface;

final class ConfigServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    const DEFAULT_PREFIX         = 'config';
    const DEFAULT_SEPARATOR      = '.';
    const DEFAULT_INFLECTORS_KEY = 'inflectors';
    const DEFAULT_DI_KEY         = 'di';

    const SETTING_PREFIX    = 'prefix';
    const SETTING_SEPARATOR = 'separator';

    /**
     * @var array
     */
    private $config;

    /**
     * @var AggregateServiceProvider
     */
    private $subProviders;

    /**
     * @api
     *
     * @param array|ApplicationConfig $config
     * @param array                   $settings
     *
     * @return ConfigServiceProvider
     */
    public static function fromConfig($config, array $settings = [])
    {
        return new self(
            $config,
            self::getSettingOrDefault(self::SETTING_PREFIX, $settings, self::DEFAULT_PREFIX),
            self::getSettingOrDefault(self::SETTING_SEPARATOR, $settings, self::DEFAULT_SEPARATOR)
        );
    }

    /**
     * @api
     *
     * @param string[] $patterns
     * @param array    $settings
     *
     * @return ConfigServiceProvider
     */
    public static function fromFiles(array $patterns, array $settings = [])
    {
        $separator = self::getSettingOrDefault(self::SETTING_SEPARATOR, $settings, self::DEFAULT_SEPARATOR);

        return self::fromConfig(ApplicationConfig::fromFiles($patterns, $separator), $settings);
    }

    /**
     * @api
     *
     * @param array|ApplicationConfig    $config
     * @param string                     $prefix
     * @param string                     $separator
     */
    public function __construct(
        $config,
        $prefix = self::DEFAULT_PREFIX,
        $separator = self::DEFAULT_SEPARATOR
    ) {
        $this->config = [];

        $config = ($config instanceof ApplicationConfig) ? $config : new ApplicationConfig($config, $separator);

        $configurator = new League\Configurator();

        $configurator->addApplicationConfig(null, $config, $prefix);

        if (isset($config[self::DEFAULT_DI_KEY])) {
            $configurator->addServiceConfig(null, new ServiceConfig($config[self::DEFAULT_DI_KEY]));
        }

        if (isset($config[self::DEFAULT_INFLECTORS_KEY])) {
            $configurator->addInflectorConfig(null, new InflectorConfig($config[self::DEFAULT_INFLECTORS_KEY]));
        }

        $this->subProviders = $configurator->getServiceProvider();
    }

    public function provides($service = null)
    {
        return $this->subProviders->provides($service);
    }

    public function register()
    {
        $this->subProviders->register();
    }

    public function boot()
    {
        $this->subProviders->boot();
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->subProviders->setContainer($container);
    }

    public function getContainer()
    {
        return $this->subProviders->getContainer();
    }

    /**
     * @param string $name
     * @param array  $settings
     * @param mixed  $default
     *
     * @return mixed
     */
    private static function getSettingOrDefault($name, array $settings, $default)
    {
        return isset($settings[$name]) ? $settings[$name] : $default;
    }
}
