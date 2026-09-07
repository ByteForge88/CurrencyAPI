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

namespace byteforge88\currencyapi\utils;

use pocketmine\utils\Config;

use byteforge88\currencyapi\CurrencyAPI;

class Utils {

    public const CONFIG_VERSION = 1;
    public const MESSAGES_VERSION = 1;

    public static function checkConfig(
        Config $config,
        string $versionKey,
        int $expectedVersion
    ) : void{
        $currentVersion = $config->get($versionKey);

        if ($currentVersion === $expectedVersion) {
            return;
        }

        $currencyapi = CurrencyAPI::getInstance();

        $path = $config->getPath();
        $backup = str_replace(
            ".yml",
            "-" . date("m-d-Y") . ".yml",
            $path
        );

        rename($path, $backup);

        $currencyapi->saveResource(basename($path), true);

        $currencyapi->getLogger()->warning(basename($path) . " was outdated.");
        $currencyapi->getLogger()->warning("Old file renamed to " . basename($backup));
        $currencyapi->getLogger()->warning("Generated a new " . basename($path) . ".");
    }
}