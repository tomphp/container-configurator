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

    const SETTING_PREFIX    = 'prefix';
    const SETTING_SEPARATOR = 'separator';

    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $separator;

    /**
     * @var ConfigurableServiceProvider[]
     */
    private $subProviders;

    /**
     * @api
     *
     * @param array $config
     * @param array $settings
     *
     * @return ConfigServiceProvider
     */
    public static function fromConfig(array $config, array $settings = [])
    {
        return new self(
            $config,
            self::getSettingOrDefault(self::SETTING_PREFIX, $settings, self::DEFAULT_PREFIX),
            self::getSettingOrDefault(self::SETTING_SEPARATOR, $settings, self::DEFAULT_SEPARATOR),
            [self::DEFAULT_INFLECTORS_KEY => new InflectorConfigServiceProvider([])]
        );
    }

    /**
     * @api
     *
     * @param array                         $config
     * @param string                        $prefix
     * @param string                        $separator
     * @param ConfigurableServiceProvider[] $subProviders
     */
    public function __construct(
        array $config,
        $prefix = self::DEFAULT_PREFIX,
        $separator = self::DEFAULT_SEPARATOR,
        array $subProviders = []
    ) {
        $this->prefix       = $prefix;
        $this->separator    = $separator;
        $this->subProviders = $subProviders;

        $config = $this->expandSubGroups($config);

        $this->provides = $this->getKeys($config);

        $this->config = array_combine($this->provides, array_values($config));

        foreach ($this->subProviders as $key => $provider) {
            $this->configureSubProvider($key, $config, $provider);
        }
    }

    public function register()
    {
        foreach ($this->config as $key => $value) {
            $this->getContainer()->add($key, $value);
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
     * @param array $config
     * @return array
     */
    private function expandSubGroups(array $config)
    {
        $expanded = [];

        foreach ($config as $key => $value) {
            $expanded += $this->expandSubGroup($key, $value);
        }

        return $expanded;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return array
     */
    private function expandSubGroup($key, $value)
    {
        if (!is_array($value)) {
            return [$key => $value];
        }

        $expanded = [$key => $value];

        foreach ($value as $subkey => $subvalue) {
            $expanded += $this->expandSubGroup(
                $key. $this->separator . $subkey,
                $subvalue
            );
        }

        return $expanded;
    }

    /**
     * @param array $config
     * @return array
     */
    private function getKeys(array $config)
    {
        $keys = array_keys($config);

        if (!empty($this->prefix)) {
            $keys = $this->addPrefix($keys);
        }

        return $keys;
    }

    /**
     * @param array $keys
     * @return array
     */
    private function addPrefix(array $keys)
    {
        return array_map(
            function ($key) {
                return $this->prefix . $this->separator . $key;
            },
            $keys
        );
    }

    /**
     * @param string $key
     */
    private function configureSubProvider($key, $config, ConfigurableServiceProvider $provider)
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
