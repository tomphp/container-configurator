<?php

namespace TomPHP\ContainerConfigurator\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

final class UnsupportedFeatureException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @param string $containerName
     *
     * @return self
     */
    public static function forInflectors($containerName)
    {
        return self::create('%s does not support inflectors.', [$containerName]);
    }
}
