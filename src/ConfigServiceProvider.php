<?php

namespace TomPHP\ConfigServiceProvider;

use League\Container\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
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
     * @param array  $config
     * @param string $prefix
     * @param string $separator
     */
    public function __construct(array $config, $prefix = 'config', $separator = '.')
    {
        $this->prefix    = $prefix;
        $this->separator = $separator;

        $config = $this->expandSubGroups($config);

        $this->provides = $this->getKeys($config);

        $this->config = array_combine($this->provides, array_values($config));
    }

    public function register()
    {
        foreach ($this->config as $key => $value) {
            $this->getContainer()->add($key, $value);
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
}
