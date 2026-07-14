<?php

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