<?php

namespace TomPHP\ConfigServiceProvider\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

final class NoMatchingFilesException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @param string $pattern
     *
     * @return self
     */
    public static function fromPattern($pattern)
    {
        return self::create('No files found matching pattern: "%s".', [$pattern]);
    }
}
