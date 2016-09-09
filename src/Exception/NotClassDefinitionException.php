<?php

namespace TomPHP\ConfigServiceProvider\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

final class NotClassDefinitionException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
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
