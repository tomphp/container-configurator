<?php

namespace TomPHP\ConfigServiceProvider\FileReader;

interface FileReader
{
    /**
     * @param string $filename
     *
     * @return array
     */
    public function read($filename);
}
