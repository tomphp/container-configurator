<?php

namespace TomPHP\ConfigServiceProvider\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

final class ReadOnlyException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @param string $name
     *
     * @return self
     */
    public static function fromClassName($name)
    {
        return self::create('"%s" is read only.', [$name]);
    }
}
