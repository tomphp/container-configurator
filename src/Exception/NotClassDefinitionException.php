<?php

namespace TomPHP\ConfigServiceProvider\Exception;

use LogicException;

final class NotClassDefinitionException extends LogicException implements Exception
{
    use ExceptionFactory;

    /**
     * @param string $name
     *
     * @return self
     */
    public static function fromServiceName($name)
    {
        return self::create(
            'Service configuration for "%s" did not create a class definition.',
            $name
        );
    }
}
