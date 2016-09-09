<?php

namespace TomPHP\ConfigServiceProvider\Exception;

use DomainException;

final class UnknownSettingException extends DomainException implements Exception
{
    use ExceptionFactory;

    /**
     * @param string   $setting
     * @param string[] $knownSettings
     *
     * @return self
     */
    public static function fromSetting($setting, array $knownSettings)
    {
        return self::create(
            'Setting "%s" is unknown; valid settings are %s.',
            $setting,
            self::optionsToString($knownSettings)
        );
    }
}
