<?php

namespace TomPHP\ContainerConfigurator\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

/**
 * @api
 */
final class NoMatchingFilesException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     *
     * @param string $pattern
     *
     * @return self
     */
    public static function fromPattern($pattern)
    {
        return self::create('No files found matching pattern: "%s".', [$pattern]);
    }
}
