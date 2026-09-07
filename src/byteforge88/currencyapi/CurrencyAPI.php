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

use pocketmine\plugin\PluginBase;

use pocketmine\Player;

use pocketmine\utils\Config;

use byteforge88\currencyapi\api\Currency;

use byteforge88\currencyapi\database\Database;

use byteforge88\currencyapi\utils\Utils;

use byteforge88\currencyapi\command\BalanceCommand;
use byteforge88\currencyapi\command\SeeBalanceCommand;
use byteforge88\currencyapi\command\TopBalanceCommand;
use byteforge88\currencyapi\command\PayCommand;

class CurrencyAPI extends PluginBase {

    protected static ?self $instance = null;

    private Currency $currency;

    public Config $messages;

    public static function getInstance() : ?self{ return self::$instance; }

    public function onLoad() : void{
        if (self::$instance === null) {
            self::$instance = $this;
        }

        $this->getLogger()->info("Checking to see if the user is running a dev build...");
    }

    public function onEnable() : void{
        if (Version::IS_DEVELOPMENT_BUILD === true) {
            $this->getLogger()->info("You are running a dev build, there may be bugs present!");
        }

        $this->saveDefaultConfig();
        $this->saveResource("messages.yml");

        $server = $this->getServer();
        $this->currency = new Currency();
        $this->messages = new Config($this->getDataFolder() . "messages.yml");

        $server->getPluginManager()->registerEvents(new EventListener(), $this);

        $server->getCommandMap()->registerAll("CurrencyAPI", [
            new BalanceCommand($this),
            new SeeBalanceCommand($this),
            new TopBalanceCommand($this),
            new PayCommand($this)
        ]);

        Utils::checkConfig($this->getConfig(), "config-version", Utils::CONFIG_VERSION);
        Utils::checkConfig($this->messages, "messages-version", Utils::MESSAGES_VERSION);
    }

    public function onDisable() : void{
        if (self::$instance === $this) {
            self::$instance = null;
        }

        Database::getInstance()->close();
    }

    public function isNew(Player|string $player) : bool{
        $player = $player instanceof Player ? $player->getName() : $player;
        
        return $this->currency->isNew($player);
    }

    public function insertIntoDatabase(Player|string $player, int $balance = 1000) : void{
        $player = $player instanceof Player ? $player->getName() : $player;

        $this->currency->insertIntoDatabase($player, $balance);
    }

    public function getBalance(Player|string $player) : ?int{
        return $this->currency->getBalance($player);
    }

    public function getTopBalances(int $limit = 10) : array{
        return $this->currency->getTopBalances($limit);
    }

    public function addMoney(Player|string $player, int $amount = 1) : void{
        $player = $player instanceof Player ? $player->getName() : $player;

        $this->currency->addMoney($player, $amount);
    }

    public function setMoney(Player|string $player, int $amount = 1) : void{
        $player = $player instanceof Player ? $player->getName() : $player;

        $this->currency->setMoney($player, $amount);
    }

    public function removeMoney(Player|string $player, int $amount = 1) : void{
        $player = $player instanceof Player ? $player->getName() : $player;

        $this->currency->removeMoney($player, $amount);
    }

    public function formatMoney(int $amount) : string{
        return $this->currency->formatMoney($amount);
    }
}