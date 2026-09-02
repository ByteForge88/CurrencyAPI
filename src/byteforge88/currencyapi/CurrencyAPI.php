<?php

declare(strict_types=1);

namespace byteforge88\currencyapi;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;

use byteforge88\currencyapi\database\Database;

use byteforge88\currencyapi\utils\Utils;

class CurrencyAPI extends PluginBase {
    
    protected static ?self $instance = null;

    public Config $messages;

    public static function getInstance() : self{ return self::$instance; }
    
    public function onLoad() : void{
        if (self::$instance === null) {
            self::$instance = $this;
        }
    }
    
    public function onEnable() : void{
        $this->saveDefaultConfig();
        $this->saveResource("messages.yml");

        $this->currency = new CurrencyAPI();
        
        $this->messages = new Config($this->getDataFolder() . "messages.yml");
        
        Utils::checkConfig($this->getConfig(), "config-version", Utils::CONFIG_VERSION);
        Utils::checkConfig($this->messages, "messages-version", Utils::MESSAGES_VERSION);
    }
    
    public function onDisable() : void{
        if (self::$instance === $this) {
            self::$instance = null;
        }
        
        Database::getInstance()->close();
    }

    public function isNew(Player|string $player, string $currencyType = "money") : bool{
        return $this->currencyapi->isNew($player, $currencyType);
    }

    public function insertIntoDatabase(
        Player|string $player,
        string $currencyType = "money",
        int $balance = 1000
    ) : void{
        $this->currencyapi->insertIntoDatabase($player, $currencyType, $balance);
    }

    public function getBalance(Player|string $player, string $currencyType = "money") : ?int{
        return $this->getBalance($player, $currencyType);
    }

    public function getTopBalances(int $limit = 10) : ?array{}

    public function addMoneyToBalance(
        Player|string $player,
        string $currencyType = "money",
        int $amount = 1
    ) : void{
        $this->currencyapi->addMoneyToBalance($player, $currencyType, $amount);
    }

    public function setBalance(
        Player|string $player,
        string $currencyType = "money",
        int $amount = 1
    ) : void{
        $this->currencyapi->setBalance($player, $currencyType, $amount);
    }

    public function removeMoneyFromBalance(
        Player|string $player,
        string $currencyType = "money",
        int $amount = 1
    ) : void{
        $this->currencyapi->removeMoneyFromBalance($player, $currencyType, $amount);
    }
}