<?php

namespace TomPHP\ContainerConfigurator\FileReader;

use Assert\Assertion;
use InvalidArgumentException;

final class FileLocator
{
    /**
     * @param string $pattern
     *
     * @return string[]
     *
     * @throws InvalidArgumentException
     */
    public function locate($pattern)
    {
        Assertion::string($pattern);

        return glob($pattern, GLOB_BRACE);
    }
}
