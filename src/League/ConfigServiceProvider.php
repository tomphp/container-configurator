<?php

namespace TomPHP\ConfigServiceProvider\League;

use League\Container\ServiceProvider\AbstractServiceProvider;
use TomPHP\ConfigServiceProvider\Config;
use TomPHP\ConfigServiceProvider\ConfigIterator;

final class ConfigServiceProvider extends AbstractServiceProvider
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @param Config $config
     * @param string $prefix
     */
    public function __construct(Config $config, $prefix)
    {
        $this->prefix   = $prefix;
        $this->config   = $config;
        $this->provides = array_map(
            function ($key) {
                return $this->keyPrefix() . $key;
            },
            $config->getKeys()
        );
    }

    public function register()
    {
        $container = $this->getContainer();
        $iterator  = new ConfigIterator($this->config);
        $prefix    = $this->keyPrefix();

        foreach ($iterator as $key => $value) {
            $this->getContainer()->share($prefix . $key, function () use ($value) {
                return $value;
            });
        }
    }

    /**
     * @return string
     */
    private function keyPrefix()
    {
        if (empty($this->prefix)) {
            return '';
        }

        return $this->prefix . $this->config->getSeparator();
    }
}
