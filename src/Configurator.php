<?php

namespace TomPHP\ConfigServiceProvider;

use TomPHP\ConfigServiceProvider\Exception\NoMatchingFilesException;
use TomPHP\ConfigServiceProvider\Exception\UnknownSettingException;
use Assert\Assertion;

final class Configurator
{
    const FILE_READERS = [
        '.json' => FileReader\JSONFileReader::class,
        '.php'  => FileReader\PHPFileReader::class,
    ];

    const CONTAINER_ADAPTERS = [
        \League\Container\Container::class => League\LeagueContainerAdapter::class,
        \Pimple\Container::class           => Pimple\PimpleContainerAdapter::class,
    ];

    /**
     * @var ApplicationConfig
     */
    private $config;

    /**
     * @var FileReader\ReaderFactory
     */
    private $readerFactory;

    /**
     * @var array
     */
    private $settings = [
        'config_prefix'      => 'config',
        'config_separator'   => '.',
        'services_key'       => 'di.services',
        'inflectors_key'     => 'di.inflectors',
        'singleton_services' => false,
    ];

    /**
     * @api
     *
     * @return Configurator
     */
    public static function apply()
    {
        return new self();
    }

    private function __construct()
    {
        $this->config = new ApplicationConfig([]);
    }

    /**
     * @api
     *
     * @param array $config
     *
     * @return Configurator
     */
    public function configFromArray(array $config)
    {
        $this->config->merge($config);

        return $this;
    }

    /**
     * @api
     *
     * @param string $filename
     *
     * @return Configurator
     */
    public function configFromFile($filename)
    {
        Assertion::file($filename);

        $this->readFileAndMergeConfig($filename);

        return $this;
    }

    /**
     * @api
     *
     * @param string $pattern
     *
     * @return Configurator
     *
     * @throws NoMatchingFilesException
     */
    public function configFromFiles($pattern)
    {
        Assertion::string($pattern);

        $locator = new FileReader\FileLocator();

        $files = $locator->locate($pattern);

        if (count($files) === 0) {
            throw NoMatchingFilesException::fromPattern($pattern);
        }

        foreach ($files as $filename) {
            $this->readFileAndMergeConfig($filename);
        }

        return $this;
    }

    /**
     * @api
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return Configurator
     *
     * @throws UnknownSettingException
     */
    public function withSetting($name, $value)
    {
        Assertion::string($name);
        Assertion::scalar($value);

        if (!array_key_exists($name, $this->settings)) {
            throw UnknownSettingException::fromSetting($name, array_keys($this->settings));
        }

        $this->settings[$name] = $value;

        return $this;
    }

    /**
     * @api
     *
     * @param object $container
     *
     * @return void
     */
    public function to($container)
    {
        $this->config->setSeparator($this->settings['config_separator']);

        $factory = new ContainerAdapterFactory(self::CONTAINER_ADAPTERS);

        $configurator = $factory->create($container);

        $configurator->addApplicationConfig($this->config, $this->settings['config_prefix']);

        if (isset($this->config[$this->settings['services_key']])) {
            $configurator->addServiceConfig(new ServiceConfig(
                $this->config[$this->settings['services_key']],
                $this->settings['singleton_services']
            ));
        }

        if (isset($this->config[$this->settings['inflectors_key']])) {
            $configurator->addInflectorConfig(new InflectorConfig($this->config[$this->settings['inflectors_key']]));
        }
    }

    /**
     * @param string $filename
     *
     * @return void
     */
    private function readFileAndMergeConfig($filename)
    {
        $reader = $this->getReaderFor($filename);

        $this->config->merge($reader->read($filename));
    }

    /**
     * @param string $filename
     *
     * @return FileReader\FileReader
     */
    private function getReaderFor($filename)
    {
        if (!$this->readerFactory) {
            $this->readerFactory = new FileReader\ReaderFactory(self::FILE_READERS);
        }

        return $this->readerFactory->create($filename);
    }
}
