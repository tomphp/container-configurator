<?php

namespace tests\mocks\FileReader;

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

    public function read($filename)
    {
        self::$reads[] = $filename;

        return [];
    }
}
