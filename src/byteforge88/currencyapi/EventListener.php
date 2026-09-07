<?php

declare(strict_types=1);

namespace byteforge88\currencyapi;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;

use byteforge88\currencyapi\database\Database;

class EventListener implements Listener {

    public function onLogin(PlayerLoginEvent $event) : void{
        $player = $event->getPlayer();
        $db = Database::getInstance();
        $starting_balance = CurrencyAPI::getInstance()->getConfig()->get("starting-balance");

        if ($db->isNew($player)) {
            $db->insertIntoDatabase($player, $starting_balance);
        }
    }
}