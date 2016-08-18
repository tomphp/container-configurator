<?php

namespace TomPHP\ConfigServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

final class ConfigServiceProvider extends AbstractServiceProvider implements
    BootableServiceProviderInterface
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
     * @var ConfigurableServiceProvider[]
     */
    private $subProviders;

    /**
     * @api
     *
     * @param array|Config $config
     * @param array        $settings
     *
     * @return ConfigServiceProvider
     */
    public static function fromConfig($config, array $settings = [])
    {
        return new self(
            $config,
            self::getSettingOrDefault(self::SETTING_PREFIX, $settings, self::DEFAULT_PREFIX),
            self::getSettingOrDefault(self::SETTING_SEPARATOR, $settings, self::DEFAULT_SEPARATOR),
            [
                self::DEFAULT_INFLECTORS_KEY => new InflectorConfigServiceProvider([]),
                self::DEFAULT_DI_KEY         => new DIConfigServiceProvider([]),
            ]
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
        $separator = self::getSettingOrDefault(self::SETTING_PREFIX, $settings, self::DEFAULT_PREFIX);

        return self::fromConfig(Config::fromFiles($patterns, $separator), $settings);
    }

    /**
     * @api
     *
     * @param array|Config                  $config
     * @param string                        $prefix
     * @param string                        $separator
     * @param ConfigurableServiceProvider[] $subProviders
     */
    public function __construct(
        $config,
        $prefix = self::DEFAULT_PREFIX,
        $separator = self::DEFAULT_SEPARATOR,
        array $subProviders = []
    ) {
        $this->config = [];

        $config = ($config instanceof Config) ? $config : new Config($config, $separator);

        $iterator = new ConfigIterator($config, $separator);
        $prefix = $prefix ? $prefix . $separator : '';

        foreach ($iterator as $key => $value) {
            $this->config[$prefix . $key] = $value;
        }

        $this->subProviders = $subProviders;

        $this->provides = array_keys($this->config);

        foreach ($this->subProviders as $key => $provider) {
            $this->configureSubProvider($key, $config->asArray(), $provider);
        }
    }

    public function register()
    {
        foreach ($this->config as $key => $value) {
            $this->getContainer()->add($key, function () use ($value) {
                return $value;
            });
        }

        foreach ($this->subProviders as $provider) {
            $provider->setContainer($this->getContainer());
            $provider->register();
        }
    }

    public function boot()
    {
        foreach ($this->subProviders as $provider) {
            if (!$provider instanceof BootableServiceProviderInterface) {
                continue;
            }

            $provider->setContainer($this->getContainer());
            $provider->boot();
        }
    }

    /**
     * @param string $key
     * @param array $config
     * @param ConfigurableServiceProvider $provider
     */
    private function configureSubProvider($key, array $config, ConfigurableServiceProvider $provider)
    {
        if (!array_key_exists($key, $config)) {
            return;
        }

        $provider->configure($config[$key]);

        $this->provides = array_merge($this->provides, $provider->provides());
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
