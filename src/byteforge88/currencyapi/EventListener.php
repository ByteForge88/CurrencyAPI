<?php

declare(strict_types=1);

namespace byteforge88\cuurrencyapi;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;

use byteforge88\currencyapi\database\Database;

class EventListener implements Listener {

    public function onUserJoin(PlayerLoginEvent $event) : void{
        $player = $event->getPlayer();
        $database = Database::getinstance();

        if ($database->isNew($player)) {
            $database->insertIntoDatabase($player);
        }
    }
}