<?php

namespace TomPHP\ContainerConfigurator\FileReader;

use Assert\Assertion;
use Symfony\Component\Yaml;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;
use TomPHP\ContainerConfigurator\Exception\MissingDependencyException;

/**
 * @internal
 */
final class YAMLFileReader implements FileReader
{
    /**
     * @throws MissingDependencyException
     */
    public function __construct()
    {
        if (!class_exists(Yaml\Yaml::class)) {
            throw MissingDependencyException::fromPackageName('symfony/yaml');
        }
    }

    public function read($filename)
    {
        Assertion::file($filename);

        try {
            $config = Yaml\Yaml::parse(file_get_contents($filename));
        } catch (Yaml\Exception\ParseException $exception) {
            throw InvalidConfigException::fromYAMLFileError($filename, $exception->getMessage());
        }

        return $config;
    }
}
