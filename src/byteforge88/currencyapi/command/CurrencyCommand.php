<?php

declare(strict_types=1);

namespace byteforge88\currencyapi;

use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;

use byteforge88\currencyapi\CurrencyAPI;

abstract class CurrencyCommand extends Command implements PluginIdentifiableCommand {

    private CurrencyAPI $plugin;

    public function __construct(string $name, CurrencyAPI $plugin) {
        parent::__construct($name);
        $this->plugin = $plugin;
        $this->usageMessage = "";
    }

    public function getPlugin() : CurrencyAPI{
        return $this->plugin;
    }
}