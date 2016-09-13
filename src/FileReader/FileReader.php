<?php

namespace TomPHP\ConfigServiceProvider\FileReader;

use InvalidArgumentException;
use TomPHP\ConfigServiceProvider\Exception\InvalidConfigException;

interface FileReader
{
    /**
     * @param string $filename
     *
     * @return array
     *
     * @throws InvalidConfigException
     * @throws InvalidArgumentException
     */
    public function read($filename);
}
