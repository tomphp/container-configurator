<?php

namespace TomPHP\ContainerConfigurator\Exception;

use DomainException;
use TomPHP\ExceptionConstructorTools;

final class UnknownFileTypeException extends DomainException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @param string   $extension
     * @param string[] $availableExtensions
     *
     * @return self
     */
    public static function fromFileExtension($extension, array $availableExtensions)
    {
        return self::create(
            'No reader configured for "%s" files; readers are available for %s.',
            [$extension, self::listToString($availableExtensions)]
        );
    }
}
