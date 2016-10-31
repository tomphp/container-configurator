<?php

namespace TomPHP\ContainerConfigurator\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

final class InvalidConfigException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     *
     * @param string $filename
     *
     * @return self
     */
    public static function fromPHPFileError($filename)
    {
        return self::create('"%s" does not return a PHP array.', [$filename]);
    }

    /**
     * @internal
     *
     * @param string $filename
     * @param string $message
     *
     * @return self
     */
    public static function fromJSONFileError($filename, $message)
    {
        return self::create('Invalid JSON in "%s": %s', [$filename, $message]);
    }

    /**
     * @internal
     *
     * @param string $filename
     * @param string $message
     *
     * @return self
     */
    public static function fromYAMLFileError($filename, $message)
    {
        return self::create('Invalid YAML in "%s": %s', [$filename, $message]);
    }

    /**
     * @internal
     *
     * @param string $name
     *
     * @return self
     */
    public static function fromNameWhenClassAndFactorySpecified($name)
    {
        return self::create(
            'Both "class" and "factory" are specified for service "%s"; these cannot be used together.',
            [$name]
        );
    }

    /**
     * @internal
     *
     * @param string $name
     *
     * @return self
     */
    public static function fromNameWhenClassAndServiceSpecified($name)
    {
        return self::create(
            'Both "class" and "service" are specified for service "%s"; these cannot be used together.',
            [$name]
        );
    }

    /**
     * @internal
     *
     * @param string $name
     *
     * @return self
     */
    public static function fromNameWhenFactoryAndServiceSpecified($name)
    {
        return self::create(
            'Both "factory" and "service" are specified for service "%s"; these cannot be used together.',
            [$name]
        );
    }
}
