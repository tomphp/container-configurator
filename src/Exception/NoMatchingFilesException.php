<?php

namespace TomPHP\ConfigServiceProvider\Exception;

final class NoMatchingFilesException extends \LogicException implements Exception
{
    use ExceptionFactory;

    /**
     * @param array $patterns
     *
     * @return self
     */
    public static function fromPatterns(array $patterns)
    {
        $patterns = '"' . implode('", "', $patterns) . '"';

        return self::create(
            'No files found matching patterns: [%s].',
            $patterns
        );
    }
}
