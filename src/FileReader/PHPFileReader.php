<?php

namespace TomPHP\ConfigServiceProvider\FileReader;

use TomPHP\ConfigServiceProvider\Exception\FileNotFoundException;
use TomPHP\ConfigServiceProvider\Exception\InvalidConfigException;
use TomPHP\ConfigServiceProvider\FileReader\FileReader;

final class PHPFileReader implements FileReader
{
    private $filename;

    public function read($filename)
    {
        $this->filename = $filename;

        $this->assertFileExists();

        $config = include $this->filename;

        $this->assertConfigIsValid($config);

        return $config;
    }

    private function assertFileExists()
    {
        if (!file_exists($this->filename)) {
            throw FileNotFoundException::fromFileName($this->filename);
        }
    }

    private function assertConfigIsValid($config)
    {
        if (!is_array($config)) {
            throw InvalidConfigException::fromPHPFileError($this->filename);
        }
    }
}
