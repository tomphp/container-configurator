<?php

namespace TomPHP\ConfigServiceProvider\League;

use League\Container\ServiceProvider\AbstractServiceProvider;
use TomPHP\ConfigServiceProvider\ApplicationConfig;
use TomPHP\Transform as T;
use const TomPHP\Transform\__;

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
        $this->prefix   = $prefix;
        $this->config   = $config;
        $this->provides = array_map(T\concat($this->keyPrefix(), __), $config->getKeys());
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
