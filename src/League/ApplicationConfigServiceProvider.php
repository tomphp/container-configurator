<?php

namespace TomPHP\ConfigServiceProvider\League;

use Assert\Assertion;
use League\Container\ServiceProvider\AbstractServiceProvider;
use TomPHP\ConfigServiceProvider\ApplicationConfig;

final class ApplicationConfigServiceProvider extends AbstractServiceProvider
{
    /**
     * @var ApplicationConfig
     */
    private $config;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @param ApplicationConfig $config
     * @param string            $prefix
     */
    public function __construct(ApplicationConfig $config, $prefix)
    {
        Assertion::string($prefix);

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
        $prefix = $this->keyPrefix();

        foreach ($this->config as $key => $value) {
            $this->container->share($prefix . $key, function () use ($value) {
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
