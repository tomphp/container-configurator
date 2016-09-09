<?php

namespace TomPHP\ConfigServiceProvider\FileReader;

use Assert\Assertion;
use TomPHP\ConfigServiceProvider\Exception\InvalidConfigException;

final class PHPFileReader implements FileReader
{
    private $filename;

    public function read($filename)
    {
        Assertion::file($filename);

        $this->filename = $filename;

        $config = include $this->filename;

        $this->assertConfigIsValid($config);

        return $config;
    }

    private function assertConfigIsValid($config)
    {
        if (!is_array($config)) {
            throw InvalidConfigException::fromPHPFileError($this->filename);
        }
    }
}
