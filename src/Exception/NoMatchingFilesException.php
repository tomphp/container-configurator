<?php

namespace TomPHP\ConfigServiceProvider\Exception;

use LogicException;

final class NoMatchingFilesException extends LogicException implements Exception
{
    use ExceptionFactory;

    /**
     * @param string $pattern
     *
     * @return self
     */
    public static function fromPattern($pattern)
    {
        return self::create('No files found matching pattern: "%s".', $pattern);
    }
}
