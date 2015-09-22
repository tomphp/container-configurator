<?php

namespace TomPHP\ConfigServiceProvider;

interface FileReader
{
    /**
     * @param string $filename
     *
     * @return array
     */
    public function read($filename);
}
