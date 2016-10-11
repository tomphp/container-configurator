<?php

namespace TomPHP\ContainerConfigurator;

use Assert\Assertion;
use InvalidArgumentException;
use TomPHP\ContainerConfigurator\Exception\NoMatchingFilesException;
use TomPHP\ContainerConfigurator\Exception\UnknownSettingException;

final class Configurator
{
    const SETTING_PREFIX                     = 'config_prefix';
    const SETTING_SEPARATOR                  = 'config_separator';
    const SETTING_SERVICES_KEY               = 'services_key';
    const SETTING_INFLECTORS_KEY             = 'inflectors_key';
    const SETTING_DEFAULT_SINGLETON_SERVICES = 'default_singleton_services';

    const FILE_READERS = [
        '.json' => FileReader\JSONFileReader::class,
        '.php'  => FileReader\PHPFileReader::class,
        '.yaml' => FileReader\YAMLFileReader::class,
        '.yml'  => FileReader\YAMLFileReader::class,
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
     * @var mixed[]
     */
    private $settings = [
        self::SETTING_PREFIX                     => 'config',
        self::SETTING_SEPARATOR                  => '.',
        self::SETTING_SERVICES_KEY               => 'di.services',
        self::SETTING_INFLECTORS_KEY             => 'di.inflectors',
        self::SETTING_DEFAULT_SINGLETON_SERVICES => false,
    ];

    /**
     * @var string[]
     */
    private $fileReaders = self::FILE_READERS;

    /**
     * @var string
     */
    private static $containerIdentifier;

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
     * @return string
     */
    public static function container()
    {
        if (!self::$containerIdentifier) {
            self::$containerIdentifier = uniqid(__CLASS__ . '::CONTAINER_ID::');
        }

        return self::$containerIdentifier;
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
     * @throws InvalidArgumentException
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
     * @throws NoMatchingFilesException
     * @throws InvalidArgumentException
     *
     * @return Configurator
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
     * @throws UnknownSettingException
     * @throws InvalidArgumentException
     *
     * @return Configurator
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
     * @param $extension string
     * @param $className string
     *
     * @return Configurator
     */
    public function withFileReader($extension, $className)
    {
        $this->fileReaders[$extension] = $className;

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
        $this->config->setSeparator($this->settings[self::SETTING_SEPARATOR]);

        $factory = new ContainerAdapterFactory(self::CONTAINER_ADAPTERS);

        $configurator = $factory->create($container);

        $configurator->addApplicationConfig($this->config, $this->settings[self::SETTING_PREFIX]);

        if (isset($this->config[$this->settings[self::SETTING_SERVICES_KEY]])) {
            $configurator->addServiceConfig(new ServiceConfig(
                $this->config[$this->settings[self::SETTING_SERVICES_KEY]],
                $this->settings[self::SETTING_DEFAULT_SINGLETON_SERVICES]
            ));
        }

        if (isset($this->config[$this->settings[self::SETTING_INFLECTORS_KEY]])) {
            $configurator->addInflectorConfig(new InflectorConfig(
                $this->config[$this->settings[self::SETTING_INFLECTORS_KEY]]
            ));
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
            $this->readerFactory = new FileReader\ReaderFactory($this->fileReaders);
        }

        return $this->readerFactory->create($filename);
    }
}
