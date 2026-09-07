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

namespace byteforge88\currencyapi\api;

use pocketmine\Player;

use byteforge88\currencyapi\CurrencyAPI;

use byteforge88\currencyapi\database\Database;

class Currency {

    public function isNew(Player|string $player) : bool{
        $player = $player instanceof Player ? $player->getName() : $player;
        
        return Database::getInstance()->isNew($player);
    }

    public function insertIntoDatabase(Player|string $player, int $balance = 1000) : void{
        $player = $player instanceof Player ? $player->getName() : $player;

        Database::getInstance()->insertIntoDatabase($player, $balance);
    }

    public function getBalance(Player|string $player) : ?int{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->modifyData()->prepare("SELECT balance FROM balances WHERE user = :user;");

        try {
            $stmt->bindValue(":user", $player, SQLITE3_TEXT);

            $result = $stmt->execute();
            $data = $result->fetchArray(SQLITE3_ASSOC);

            $result->finalize();

            return $data === false ? null : (int) $data["balance"];
        } finally {
            $stmt->close();
        }
    }

    public function getTopBalances(int $limit = 10) : array{
        $stmt = Database::getInstance()->modifyData()->prepare("
            SELECT
            user, balance
            FROM
            balances
            ORDER BY
            balance
            DESC LIMIT
            :limit
        ");

        try {
            $stmt->bindValue(":limit", $limit, SQLITE3_INTEGER);

            $result = $stmt->execute();

            $data = [];

            while ($r = $result->fetchArray(SQLITE3_ASSOC)) {
                $data[] = [
                    "user" => $r["user"],
                    "balance" => $r["balance"]
                ];
            }

            return $data;
        } finally {
            $stmt->close();
        }
    }

    public function addMoney(Player|string $player, int $amount = 1) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->modifyData()->prepare("
            UPDATE
            balances
            SET
            balance = balance + :amount
            WHERE
            user = :user;
        ");

        try {
            $stmt->bindValue(":user", $player, SQLITE3_TEXT);
            $stmt->bindValue(":amount", $amount, SQLITE3_INTEGER);

            $result = $stmt->execute();

            $result->finalize();
        } finally {
            $stmt->close();
        }
    }

    public function setMoney(Player|string $player, int $amount = 1) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->modifyData()->prepare("
            UPDATE
            balances
            SET
            balance = :amount
            WHERE
            user = :user;
        ");

        try {
            $stmt->bindValue(":user", $player, SQLITE3_TEXT);
            $stmt->bindValue(":amount", $amount, SQLITE3_INTEGER);

            $result = $stmt->execute();

            $result->finalize();
        } finally {
            $stmt->close();
        }
    }

    public function removeMoney(Player|string $player, int $amount = 1) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->modifyData()->prepare("
            UPDATE
            balances
            SET
            balance = balance - :amount
            WHERE
            user = :user;
        ");

        try {
            $stmt->bindValue(":user", $player, SQLITE3_TEXT);
            $stmt->bindValue(":amount", $amount, SQLITE3_INTEGER);

            $result = $stmt->execute();

            $result->finalize();
        } finally {
            $stmt->close();
        }
    }

    public function formatMoney(int|float $amount) : string{
        $n = number_format($amount);

        return $n;
    }
}