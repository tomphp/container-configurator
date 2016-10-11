<?php

namespace tests\mocks\FileReader;

use InvalidArgumentException;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;
use TomPHP\ContainerConfigurator\FileReader\FileReader;

final class CustomFileReader implements FileReader
{
    private static $reads = [];

    public static function reset()
    {
        $reads = [];
    }

    public static function getReads()
    {
        return self::$reads;
    }

    /**
     * @param string $filename
     *
     * @throws InvalidConfigException
     * @throws InvalidArgumentException
     *
     * @return array
     */
    public function read($filename)
    {
        self::$reads[] = $filename;

        return [];
    }
}
