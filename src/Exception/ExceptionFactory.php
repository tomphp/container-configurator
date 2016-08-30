<?php

namespace TomPHP\ConfigServiceProvider\Exception;

use Exception as PHPException;

trait ExceptionFactory
{
    /**
     * @param  string $message
     * @param  mixed  ...$params
     *
     * @return self
     */
    protected static function create($message)
    {
        $params = func_get_args();
        array_shift($params);

        return new self(vsprintf($message, $params));
    }
}
