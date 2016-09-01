<?php

namespace TomPHP\ConfigServiceProvider\Exception;

use LogicException;

final class UnsupportedFeatureException extends LogicException implements Exception
{
    /**
     * @param string $containerName
     *
     * @return self
     */
    public static function forInflectors($containerName)
    {
        return new self(sprintf('%s does not support inflectors.', $containerName));
    }
}
