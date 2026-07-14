<?php

declare(strict_types=1);

namespace byteforge88\currencyapi;

use pocketmine\plugin\PluginBase;

use byteforge88\currencyapi\database\Database;

use byteforge88\currencyapi\utils\Utils;

class CurrencyAPI extends PluginBase {
    
    protected static self $instance;
    
    public function onLoad() : void{
        self::$instance = $this;
    }
    
    public function onEnable() : void{
        $this->saveDefaultConfig();
        
        Utils::checkConfig($this->getConfig(), "config-version", Utils::CONFIG_VERSION);
    }
    
    public function onDisable() : void{
        Database::getInstance()->close();
    }
    
    public static function getInstance() : self{
        return self::$instance;
    }
}