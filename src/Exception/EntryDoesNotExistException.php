<?php

namespace TomPHP\ContainerConfigurator\Exception;

use DomainException;
use TomPHP\ExceptionConstructorTools;

/**
 * @api
 */
final class EntryDoesNotExistException extends DomainException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     *
     * @param string $key
     *
     * @return self
     */
    public static function fromKey($key)
    {
        return self::create('No entry found for "%s".', [$key]);
    }
}
