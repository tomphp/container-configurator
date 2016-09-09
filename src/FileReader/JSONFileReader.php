<?php

namespace TomPHP\ConfigServiceProvider\FileReader;

use TomPHP\ConfigServiceProvider\Exception\FileNotFoundException;
use TomPHP\ConfigServiceProvider\Exception\InvalidConfigException;

final class JSONFileReader implements FileReader
{
    const JSON_ERRORS = [
        JSON_ERROR_NONE             => null,
        JSON_ERROR_DEPTH            => 'Maximum stack depth exceeded',
        JSON_ERROR_STATE_MISMATCH   => 'Underflow or the modes mismatch',
        JSON_ERROR_CTRL_CHAR        => 'Unexpected control character found',
        JSON_ERROR_SYNTAX           => 'Syntax error, malformed JSON',
        JSON_ERROR_UTF8             => 'Malformed UTF-8 characters, possibly incorrectly encoded',
    ];

    /**
     * @var string
     */
    private $filename;

    public function read($filename)
    {
        $this->filename = $filename;

        $this->assertFileExists();

        $config = json_decode(file_get_contents($filename), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw InvalidConfigException::fromJSONFileError($filename, $this->getJsonError());
        }

        return $config;
    }

    private function assertFileExists()
    {
        if (!file_exists($this->filename)) {
            throw FileNotFoundException::fromFileName($this->filename);
        }
    }

    /**
     * @return string
     */
    private function getJsonError()
    {
        if (function_exists('json_last_error_msg')) {
            return json_last_error_msg();
        }

        return self::JSON_ERRORS[json_last_error()];
    }
}
