<?php

namespace TomPHP\ContainerConfigurator\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

final class MissingDependencyException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     *
     * @param string $packageName
     *
     * @return self
     */
    public static function fromPackageName($packageName)
    {
        return self::create('The package "%s" is missing. Please run "composer require %s" to install it.', [
            $packageName,
            $packageName,
        ]);
    }
}
