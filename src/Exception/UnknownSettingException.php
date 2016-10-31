<?php

namespace TomPHP\ContainerConfigurator\Exception;

use DomainException;
use TomPHP\ExceptionConstructorTools;

final class UnknownSettingException extends DomainException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     *
     * @param string   $setting
     * @param string[] $knownSettings
     *
     * @return self
     */
    public static function fromSetting($setting, array $knownSettings)
    {
        return self::create(
            'Setting "%s" is unknown; valid settings are %s.',
            [$setting, self::listToString($knownSettings)]
        );
    }
}
