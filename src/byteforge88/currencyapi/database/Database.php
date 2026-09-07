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

namespace byteforge88\currencyapi\database;

use SQLite3;

use pocketmine\Player;

use pocketmine\utils\SingletonTrait;

use byteforge88\currencyapi\CurrencyAPI;

class Database {
    use SingletonTrait;

    protected ?SQLite3 $sql_connection = null;

    private function __construct() {
        $folder = CurrencyAPI::getInstance()->getDataFolder() . "database/";

        @mkdir($folder);

        if ($this->sql_connection === null) {
            $this->sql_connection = new SQLite3($folder . "database.db");

            $this->sql_connection->exec("CREATE TABLE IF NOT EXISTS balances (user TEXT PRIMARY KEY, balance INT);");
        }
    }

    public function close() : void{
        $this->sql_connection->close();
    }

    public function modifyData() : ?SQLite3{
        return $this->sql_connection;
    }

    public function isNew(Player|string $player) : bool{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = $this->sql_connection->prepare("SELECT * FROM balances WHERE user = :user;");

        try {
            $stmt->bindValue(":user", $player, SQLITE3_TEXT);

            $result = $stmt->execute();
            $data = $result->fetchArray(SQLITE3_ASSOC);

            $result->finalize();

            return $data === false ? true : false;
        } finally {
            $stmt->close();
        }
    }

    public function insertIntoDatabase(Player|string $player, int $balance = 1000) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = $this->sql_connection->prepare("
            INSERT INTO balances
            (user, balance)
            VALUES
            (:user, :balance)
        ");

        try {
            $stmt->bindValue(":user", $player, SQLITE3_TEXT);
            $stmt->bindValue(":balance", $balance, SQLITE3_INTEGER);

            $result = $stmt->execute();

            $result->finalize();
        } finally {
            $stmt->close();
        }
    }
}