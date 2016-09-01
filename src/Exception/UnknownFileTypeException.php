<?php

namespace TomPHP\ConfigServiceProvider\Exception;

use DomainException;

final class UnknownFileTypeException extends DomainException implements Exception
{
    use ExceptionFactory;

    /**
     * @param string   $extension
     * @param string[] $availableExtensions
     *
     * @return self
     */
    public static function fromFileExtension($extension, array $availableExtensions)
    {
        return self::create(
            'No reader configured for "%s" files; readers are available for [%s].',
            $extension,
            '"' . implode('", "', $availableExtensions) . '"'
        );
    }
}
