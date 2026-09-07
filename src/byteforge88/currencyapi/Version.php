<?php

declare(strict_types=1);

namespace byteforge\currencyapi;

class Version {

    //if updating, bump both plugin.yml and this.
    public const MAIN_VERSION = "1.0.0";

    //same with this. if updating, bump both plugin.yml and this.
    public const MAIN_API = "3.0.0";

    //just know by putting this to true/false will result
    //in nothing...
    //LEAVE AS IS!
    public const IS_DEVELOPMENT_BUILD = true;

    public static function getFormattedVersion(bool $option = false) : string{
        if ($option) {
            return "version " . self::MAIN_VERSION;
        }

        return "v" . self::MAIN_VERSION;
    }

    public static function getAPI() : string{
        return self::MAIN_API;
    }
}