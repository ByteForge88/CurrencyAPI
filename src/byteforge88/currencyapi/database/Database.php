<?php

declare(strict_types=1);

namespace byteforge88\currencyapi;

use SQLite3;

use pocketmine\utils\SingletonTrait;

use byteforge88\currencyapi\CurrencyAPI;

use byteforge88\currencyapi\exception\CurrencyTypeException;

class Database {
    use SingletonTrait;

    protected SQLite3 $sql;

    public ?array $currencyTypes = null;
    
    public function __construct() {
        $currencyapi = CurrencyAPI::getInstance();
        $config = $currencyapi->getConfig();
        $folder = $currencyapi->getDataFolder() . "database/";

        @mkdir($folder);

        $this->sql = new SQLite3($folder . "database.db");

        if ((bool) $config->get("enable-multi-economy")) {
            $this->currencyTypes = $currencyapi->getConfig()->get("currency-types", []);
            $random = $this->fetchCurrencyType($this->currencyTypes);
            $this->sql->exec("CREATE TABLE IF NOT EXISTS $random (player TEXT PRIMARY KEY, int BALANCE);");
        } else {
            $this->sql->exec("CREATE TABLE IF NOT EXISTS money (player TEXT PRIMARY KEY, int BALANCE);");
        }
   }

    public function close() : void{
        $this->sql->close();
    }

    public function getSQL() : SQLite3{
        return $this->sql;
    }

    public function isNew(Player|string $target, string $currencyType = "money") : bool{
        $player = $target instanceof Player ? $player->getName() : $target;

        if ((bool) $config->get("enable-multi-economy")) {
            if (!$this->isCurrencyType($currencyType)) {
                throw new CurrencyTypeException("Invalid currency type: '" . $currencyType . "'");
            }
        }
        
        $stmt = $this->sql->("SELECT * FROM $currencyType WHERE player = :target;");

        try {
            $stmt->bindValue(":target", $target, SQLITE3_TEXT);

            $result = $stmt->execute();
            $data = $result->fetchArray(SQLITE3_ASSOC);

            $result->finalize();

            return $data === false ? true : false;
        } finally {
            $stmt->close();
        }
    }

    public function insertIntoDatabase(
        Player|string $target,
        string $currencyType = "money",
        int $balance = 1000
    ) : void{
        $player = $target instanceof Player ? $player->getName() : $player;
        
        if ((bool) $config->get("enable-multi-economy")) {
            if (!$this->isCurrencyType($currencyType)) {
                throw new CurrencyTypeException("Invalid currency type: '" . $currencyType . "'");
            }
        }
        
        $stmt = $this->sql->("
            INSERT INTO
            $currencyType
            (player, balance)
            VALUES
            (:target, :balance)
        ");

        try {
            $stmt->bindValue(":target", $target, SQLITE3_TEXT);
            $stmt->bindValue(":balance", $balance, SQLITE3_INT);

            $result = $stmt->execute();

            $result->finalize();
        } finally {
            $stmt->close();
        }
    }

    public function isCurrencyType(string $type) : bool{
        if (in_array($type, $this->currencyTypes)) {
            return true;
        }

        return false;
    }

    public function fetchCurrencyType(array $types) : string{
        $random = array_rand($types);

        return $types[$random];
    }

    public function getDefaultCurrency() : string{
        return CurrencyAPI::getInstance()->getConfig()->get("default-currency");
    }
}