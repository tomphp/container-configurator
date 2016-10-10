<?php

namespace TomPHP\ContainerConfigurator\FileReader;

use Assert\Assertion;
use InvalidArgumentException;

/**
 * @internal
 */
final class FileLocator
{
    /**
     * @param string $pattern
     *
     * @throws InvalidArgumentException
     *
     * @return string[]
     */
    public function locate($pattern)
    {
        Assertion::string($pattern);

        return glob($pattern, GLOB_BRACE);
    }
}
