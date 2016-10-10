<?php

namespace TomPHP\ContainerConfigurator\FileReader;

use InvalidArgumentException;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;

/**
 * @api
 */
interface FileReader
{
    /**
     * @param string $filename
     *
     * @throws InvalidConfigException
     * @throws InvalidArgumentException
     *
     * @return array
     */
    public function read($filename);
}
