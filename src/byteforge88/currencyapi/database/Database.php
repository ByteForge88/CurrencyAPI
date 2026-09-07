<?php

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