<?php

namespace TomPHP\ConfigServiceProvider\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

final class FileNotFoundException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @param string $filename
     *
     * @return self
     */
    public static function fromFileName($filename)
    {
        return self::create('"%s" does not exist', [$filename]);
    }
}
