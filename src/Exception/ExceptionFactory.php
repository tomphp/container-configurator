<?php

namespace TomPHP\ConfigServiceProvider\Exception;

trait ExceptionFactory
{
    /**
     * @param string $message
     * @param mixed  ...$params
     *
     * @return self
     */
    protected static function create($message)
    {
        $params = func_get_args();
        array_shift($params);

        return new self(vsprintf($message, $params));
    }

    /**
     * @param string[] $options
     *
     * @return string
     */
    protected static function optionsToString(array $options)
    {
        if (empty($options)) {
            return '[]';
        }

        return '["' . implode('", "', $options) . '"]';
    }
}
