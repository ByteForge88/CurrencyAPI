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

namespace byteforge88\currencyapi\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

use pocketmine\network\mcpe\protocol\types\CommandParameter;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

use pocketmine\Player;

use byteforge88\currencyapi\command\utils\CommandDetails;

use byteforge88\currencyapi\CurrencyAPI;

use byteforge88\currencyapi\utils\Message;

class SeeBalanceCommand extends CurrencyCommand {

    public function __construct(protected CurrencyAPI $plugin) {
        parent::__construct(CommandDetails::COMMAND_NAME_SEEBAL, $this->plugin);
        $this->setDescription(CommandDetails::COMMAND_DESC_SEEBAL);
        $this->setUsage(CommandDetails::COMMAND_USAGE_SEEBAL);
        $this->setAliases(CommandDetails::COMMAND_ALIASES_SEEBAL);
        $this->setParameter(new CommandParameter(
            "target",
            AvailableCommandsPacket::ARG_TYPE_TARGET,
            false
        ), 0);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage((string) new Message("use-command-ingame"));
            return;
        }

        if (!isset($args[0])) {
            throw new InvalidCommandSyntaxException();
        }

        if ($this->plugin->isNew($args[0])) {
            $sender->sendMessage((string) new Message("player-not-found", ["{name}"], [$args[0]]));
            return;
        }

        $c = CurrencyAPI::getInstance();
        $symbol = $c->getConfig()->get("currency-symbol");
        $balance = $c->getBalance($args[0]);
        $f_balance = $c->formatMoney($balance);

        $sender->sendMessage((string) new Message(
            "other-balance",
            ["{name}", "{balance}", "{symbol}"], 
            [$args[0], $f_balance, $symbol]
        ));
    }
}