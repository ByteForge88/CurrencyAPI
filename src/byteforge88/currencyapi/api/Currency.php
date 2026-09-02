<?php

declare(strict_types=1);

namespace byteforge88\currencyapi;

use pocketmine\Player;

use byteforge88\currencyapi\CurrencyAPI;

use byteforge88\currencyapi\database\Database;

use byteforge88\currencyapi\exception\CurrencyTypeException;
use byteforge88\currencyapi\exception\InvalidAmountException;

class Currency {

    //TODO: Add a negative balance counter
    //AKA make it functional
    public const MIN_NEGATIVE_AMOUNT = -1;
    public const MAX_NEGATIVE_AMOUNT = -10000000000;

    public const MIN_AMOUNT = 0;
    public const MAX_AMOUNT = 10000000000;

    public function isNew(Player|string $player, string $currencyType = "money") : bool{
        return Database::getInstance()->isNew($player, $currencyType);
    }

    public function getBalance(Player|string $player, string $currencyType = "money") : ?int{
        $player = $player instanceof Player ? $player->getName() : $player;
        $database = Database::getInstance();

        if ((bool) $database->config->get("enable-multi-economy")) {
            if (!$database->isCurrencyType($currencyType)) {
                throw new CurrencyTypeException("Invalid currency type: '" . $currencyType . "'");
            }
        }
        
        $stmt = Database::getInstance()->getSQL()->prepare("SELECT balance FROM $currencyType WHERE player = :player;");

        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);

            $result = $stmt->execute();
            $data = $result->fetchArray(SQLITE3_ASSOC);

            $result->finalize();

            return $data === false ? null : (int) $data["balance"];
        } finally {
            $stmt->close();
        }
    }

    public function getTopBalances() : ?array{}

    public function addMoneyToBalance(Player|string $player, string $currencyType = "money", int $amount = 1) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $database = Database::getInstance();
        
        if ($database->config->get("enable-multi-economy")) {
            if (!$database->isCurrencyType($currencyType)) {
                throw new CurrencyTypeException("Invalid currency type: '" . $currencyType . "'");
            }
        }

        if ($amount <= self::MIN_AMOUNT) {
            throw new InvalidAmountException(
                "Amount cannot be lower than " . self::MIN_AMOUNT . ", value: " . (string) $amount
            );
        }

        if ($amount >= self::MAX_AMOUNT) {
            throw new InvalidAmountException(
                "Amount cannot be higher than " . self::MAX_AMOUNT . ", value: " . (string) $amount
            );
        }

        $stmt = $database->getSQL()->prepare("UPDATE $currencyType SET balance = balance + :amount WHERE player = :player;");

        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            $stmt->bindValue(":amount", $amount, SQLITE3_INTEGER);

            $result = $stmt->execute();

            $result->finalize();
        } finally {
            $stmt->close();
        }
    }

    public function setBalance(Player|string $player, string $currencyType = "money", int $amount = 1) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $database = Database::getInstance();

        if ($database->config->get("enable-multi-economy")) {
            if (!$database->isCurrencyType($currencyType)) {
                throw new CurrencyTypeException("Invalid currency type: '" . $currencyType . "'");
            }
        }

        if ($amount <= self::MIN_AMOUNT) {
            throw new InvalidAmountException(
                "Amount cannot be lower than " . self::MIN_AMOUNT . ", value: " . (string) $amount
            );
        }

        if ($amount >= self::MAX_AMOUNT) {
            throw new InvalidAmountException(
                "Amount cannot be higher than " . self::MAX_AMOUNT . ", value: " . (string) $amount
            );
        }

        $stmt = $database->getSQL()->prepare("UPDATE $currencyType SET balance = :amount WHERE player = :player;");

        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            $stmt->bindValue(":amount", $amount, SQLITE3_INTEGER);

            $result = $stmt->execute();

            $result->finalize();
        } finally {
            $stmt->close();
        }
    }

    public function removeMoneyFromBalance() : void{
        
    }
}