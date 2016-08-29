<?php

namespace TomPHP\ConfigServiceProvider\Exception;

use LogicException;

final class ReadOnlyException extends LogicException implements Exception
{
    use ExceptionFactory;

    /**
     * @param string $name
     *
     * @return self
     */
    public static function fromClassName($name)
    {
        return self::create('"%s" is read only.', $name);
    }
}
