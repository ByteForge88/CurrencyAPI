<?php

declare(strict_types=1);

namespace byteforge88\currencyapi;

use pocketmine\plugin\PluginBase;

use byteforge88\currencyapi\database\Database;

use byteforge88\currencyapi\utils\Utils;

class CurrencyAPI extends PluginBase {
    
    protected static ?self $instance = null;

    public static function getInstance() : self{ return self::$instance; }
    
    public function onLoad() : void{
        if (self::$instance === null) {
            self::$instance = $this;
        }
    }
    
    public function onEnable() : void{
        $this->saveDefaultConfig();
        
        Utils::checkConfig($this->getConfig(), "config-version", Utils::CONFIG_VERSION);
    }
    
    public function onDisable() : void{
        if (self::$instance === $this) {
            self::$instance = null;
        }
        
        Database::getInstance()->close();
    }

    public function isNew(Player|string, string $currencyType = "money") : bool{}

    public function insertIntoDatabase(
        Player|string $player,
        string $currencyType = "money",
        int $balance = 1000
    ) : void{}

    public function getBalance(Player|string $player, string $currencyType = "money") : ?int{}
}