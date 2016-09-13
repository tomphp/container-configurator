<?php

namespace TomPHP\ContainerConfigurator\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

final class UnknownContainerException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @param string   $name
     * @param string[] $knownContainers
     *
     * @return self
     */
    public static function fromContainerName($name, array $knownContainers)
    {
        return self::create(
            'Container %s is unknown; known containers are %s.',
            [$name, self::listToString($knownContainers)]
        );
    }
}
