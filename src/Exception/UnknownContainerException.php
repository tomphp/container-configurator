<?php

namespace TomPHP\ConfigServiceProvider\Exception;

use LogicException;

final class UnknownContainerException extends LogicException implements Exception
{
    use ExceptionFactory;

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
            $name,
            self::optionsToString($knownContainers)
        );
    }
}
