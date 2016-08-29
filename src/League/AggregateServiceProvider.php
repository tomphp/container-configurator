<?php

namespace TomPHP\ConfigServiceProvider\League;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use League\Container\ServiceProvider\ServiceProviderInterface;
use League\Container\ContainerInterface;

final class AggregateServiceProvider extends AbstractServiceProvider implements
    BootableServiceProviderInterface
{
    /**
     * @var ServiceProviderInterface[]
     */
    private $providers;

    /**
     * @param ServiceProviderInterface[] $providers
     */
    public function __construct(array $providers)
    {
        $this->providers = $providers;

        $this->provides = array_reduce(
            $this->providers,
            function (array $provides, ServiceProviderInterface $provider) {
                return array_merge($provides, $provider->provides());
            },
            []
        );
    }

    public function setContainer(ContainerInterface $container)
    {
        parent::setContainer($container);

        foreach ($this->providers as $provider) {
            $provider->setContainer($container);
        }
    }

    public function boot()
    {
        foreach ($this->providers as $provider) {
            if ($provider instanceof BootableServiceProviderInterface) {
                $provider->boot();
            }
        }
    }

    public function register()
    {
        foreach ($this->providers as $provider) {
            $provider->register();
        }
    }
}
