<?php

namespace TomPHP\ConfigServiceProvider;

use TomPHP\ConfigServiceProvider\Exception\UnknownFileTypeException;

final class ReaderFactory
{
    /**
     * @var string[]
     */
    private $config;

    /**
     * @var FileReader[]
     */
    private $readers = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param mixed $filename
     *
     * @return FileReader
     */
    public function create($filename)
    {
        $readerClass = $this->getReaderClass($filename);

        if (!isset($this->readers[$readerClass])) {
            $this->readers[$readerClass] = new $readerClass();
        }

        return $this->readers[$readerClass];
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    private function getReaderClass($filename)
    {
        $found = false;

        foreach ($this->config as $extension => $readerClass) {
            if ($this->endsWith($filename, $extension)) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new UnknownFileTypeException(
                "Not reader class found for $filename; configured exceptions are "
                . implode(', ', array_keys($this->config))
            );
        }

        return $readerClass;
    }

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    private function endsWith($haystack, $needle)
    {
        return $needle === substr($haystack, -strlen($needle));
    }
}
