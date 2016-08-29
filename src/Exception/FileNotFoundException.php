<?php

namespace TomPHP\ConfigServiceProvider\Exception;

use LogicException;

final class FileNotFoundException extends LogicException implements Exception
{
    use ExceptionFactory;

    /**
     * @param string $filename
     *
     * @return self
     */
    public static function fromFileName($filename)
    {
        return self::create('"%s" does not exist', $filename);
    }
}
