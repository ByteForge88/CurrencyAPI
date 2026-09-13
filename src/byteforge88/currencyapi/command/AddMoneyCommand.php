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

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\utils\TextFormat;

use byteforge88\currencyapi\command\utils\CommandDetails;

use byteforge88\currencyapi\CurrencyAPI;

use byteforge88\currencyapi\api\Currency;

use byteforge88\currencyapi\utils\Message;

class AddMoneyCommand extends CurrencyCommand {

    public function __construct(protected CurrencyAPI $plugin) {
        parent::__construct(CommandDetails::COMMAND_NAME_ADDMONEY, $this->plugin);
        $this->setDescription(CommandDetails::COMMAND_DESC_ADDMONEY);
        $this->setUsage(CommandDetails::COMMAND_USAGE_ADDMONEY);
        $this->setPermission(CommandDetails::COMMAND_PERMISSION_ADDMONEY);
        $this->setParameters([
            new CommandParameter("target", AvailableCommandsPacket::ARG_TYPE_TARGET, false),
            new CommandParameter("amount", AvailableCommandsPacket::ARG_TYPE_INT, false)
        ]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage((string) new Message("use-command-ingame"));
            return;
        }

        if (!isset($args[0]) || !isset($args[1])) {
            throw new InvalidCommandSyntaxException();
        }

        if ($args[0] === "@a") {
            $sender->sendMessage(TextFormat::colorize("&cThis param is disabled."));
            return;
        }

        if ($args[0] === "@e") {
            $sender->sendMessage(TextFormat::colorize("&cThis param is disabled."));
            return;
        }

        if ($args[0] === "@n") {
            $sender->sendMessage(TextFormat::colorize("&cThis param is disabled."));
            return;
        }

        if ($args[0] === "@p") {
            $sender->sendMessage(TextFormat::colorize("&cThis param is disabled."));
            return;
        }

        if ($args[0] === "@r") {
            $sender->sendMessage(TextFormat::colorize("&eComing soon."));
            return;
        }

        $symbol = $this->plugin->getConfig()->get("successfully-added-money");

        $amount = (int) $args[1];

        if ($args[0] === "@s") {
            $this->plugin->addMoney($sender, $amount);
            $sender->sendMessage((string) new Message(
                "successfully-added-money-self",
                ["{amount}", "{symbol}"],
                [number_format($amount), $symbol]
            ));
            return;
        }

        if ($args[0] === $sender->getName()) {
            $this->plugin->addMoney($sender, $amount);
            $sender->sendMessage((string) new Message(
                "successfully-added-money-self",
                ["{amount}", "{symbol}"],
                [number_format($amount), $symbol]
            ));
            return;
        }

        if ($this->plugin->isNew($args[0])) {
            $sender->sendMessage((string) new Message("player-not-found", "{name}", $args[0]));
            return;
        }

        if (!is_numeric($amount)) {
            $sender->sendMessage((string) new Message("invalid-amount-numeric"));
            return;
        }

        if ($amount <= Currency::MIN_AMOUNT) {
            $sender->sendMessage((string) new Message("invalid-amount-negative"));
            return;
        }

        if ($amount > Currency::MAX_AMOUNT) {
            $sender->sendMessage((string) new Message(
                "invalid-amount-max", 
                "{max_amount}", 
                number_format(Currency::MAX_AMOUNT)
            ));
            return;
        }

        $this->plugin->addMoney($args[0], $amount);
        
        $sender->sendMessage((string) new Message(
            "successfully-added-money",
            ["{name}", "{amount}", "{symbol}"],
            [$args[0], number_format($amount), $symbol]
        ));

        $target = Server::getInstance()->getPlayerExact($args[0]);

        if ($target !== null) {
            $target->sendMessage((string) new Message(
                "successfully-recieved-money",
                ["{name}", "{amount}", "{symbol}"],
                [$sender->getName(), number_format($amount), $symbol]
            ));
            return;
        }
    }
}