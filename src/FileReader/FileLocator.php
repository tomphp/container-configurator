<?php

namespace TomPHP\ConfigServiceProvider\FileReader;

use Assert\Assertion;

final class FileLocator
{
    /**
     * @param string $pattern
     *
     * @return string[]
     */
    public function locate($pattern)
    {
        Assertion::string($pattern);

        return glob($pattern, GLOB_BRACE);
    }
}
