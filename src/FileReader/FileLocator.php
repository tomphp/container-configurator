<?php

namespace TomPHP\ConfigServiceProvider\FileReader;

final class FileLocator
{
    /**
     * @param string $pattern
     *
     * @return string[]
     */
    public function locate($pattern)
    {
        return glob($pattern, GLOB_BRACE);
    }
}
