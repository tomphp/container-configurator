<?php

namespace TomPHP\ContainerConfigurator\FileReader;

use InvalidArgumentException;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;

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
