<?php

namespace TomPHP\ConfigServiceProvider\FileReader;

use TomPHP\ConfigServiceProvider\Exception\InvalidConfigException;

interface FileReader
{
    /**
     * @param string $filename
     *
     * @return array
     *
     * @throws InvalidConfigException
     */
    public function read($filename);
}
