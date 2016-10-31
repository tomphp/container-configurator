<?php

namespace TomPHP\ContainerConfigurator\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

final class NotContainerAdapterException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     *
     * @param string $name
     *
     * @return self
     */
    public static function fromClassName($name)
    {
        return self::create(
            'Class "%s" is not a container adapter.',
            [$name]
        );
    }
}
