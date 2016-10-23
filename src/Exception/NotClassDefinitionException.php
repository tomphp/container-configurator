<?php

namespace TomPHP\ContainerConfigurator\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

/**
 * @api
 */
final class NotClassDefinitionException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     *
     * @param string $name
     *
     * @return self
     */
    public static function fromServiceName($name)
    {
        return self::create(
            'Service configuration for "%s" did not create a class definition.',
            [$name]
        );
    }
}
