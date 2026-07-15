<?php

declare(strict_types=1);

namespace byteforge88\currencyapi;

use pocketmine\player\Player;

use byteforge88\currencyapi\CurrencyAPI;

use byteforge88\currencyapi\database\Database;

class Currency {

    public function __construct(protected CurrencyAPI $plugin) {
    }

    public function isNew(Player|string $player, string $currencyType = "money") : bool{
        return Database::getInstance()->isNew($player, $currencyType);
    }

    public function getBalance(Player|string $player, string $currencyType = "money") : ?int{
        
    }
}