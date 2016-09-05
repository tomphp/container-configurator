<?php

namespace TomPHP\ConfigServiceProvider\Exception;

use LogicException;

final class NoMatchingFilesException extends LogicException implements Exception
{
    use ExceptionFactory;

    /**
     * @param array $patterns
     *
     * @return self
     */
    public static function fromPatterns(array $patterns)
    {
        return self::create(
            'No files found matching patterns: %s.',
            self::optionsToString($patterns)
        );
    }
}
