<?php

/*
 *   ____                                        _    ____ ___ 
 *  / ___|   _ _ __ _ __ ___ _ __   ___ _   _   / \  |  _ \_ _|
 * | |  | | | | '__| '__/ _ \ '_ \ / __| | | | / _ \ | |_) | | 
 * | |__| |_| | |  | | |  __/ | | | (__| |_| |/ ___ \|  __/| | 
 *  \____\__,_|_|  |_|  \___|_| |_|\___|\__, /_/   \_\_|  |___|
 *                                      |___/                  
 *
 * This plugin is free: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * @author ByteForge88
 * @github https://github.com/ByteForge88/CurrencyAPI
 * @license LGPL-3.0
 */

declare(strict_types=1);

namespace byteforge88\currencyapi;

class Version {

    //if updating, bump both plugin.yml and this.
    public const MAIN_VERSION = "1.0.0";

    //same with this. if updating, bump both plugin.yml and this.
    public const MAIN_API = "3.0.0";

    //just know by putting this to true/false will result-
    //in nothing, set this to true when updating...
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