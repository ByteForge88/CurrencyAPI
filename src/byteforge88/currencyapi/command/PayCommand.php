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

class PayCommand extends CurrencyCommand {

    public function __construct(protected CurrencyAPI $plugin) {
        parent::__construct(CommandDetails::COMMAND_NAME_PAY, $this->plugin);
        $this->setDescription(CommandDetails::COMMAND_DESC_PAY);
        $this->setUsage(CommandDetails::COMMAND_USAGE_PAY);
        $this->setAliases(CommandDetails::COMMAND_ALIASES_PAY);
        $this->setPermission(CommandDetails::COMMAND_PERMISSION_PAY);
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

        if ($args[0] === "@s") {
            $sender->sendMessage((string) new Message("cannot-pay-self"));
            return;
        }

        if ($args[0] === $sender->getName()) {
            $sender->sendMessage((string) new Message("cannot-pay-self"));
            return;
        }

        if ($this->plugin->isNew($args[0])) {
            $sender->sendMessage((string) new Message("player-not-found", ["{name}"], [$args[0]]));
            return;
        }

        $amount = (int) $args[1];

        if (!is_numeric($amount)) {
            $sender->sendMessage((string) new Message("invalid-amount-numeric"));
            return;
        }

        if ($amount <= Currency::MIN_AMOUNT) {
            $sender->sendMessage((string) new Message("invalid-amount-negative"));
            return;
        }

        $symbol = $this->plugin->getConfig()->get("currency-symbol");

        $user_balance = $this->plugin->getBalance($sender);
        $target_balance = $this->plugin->getBalance($args[0]);

        if ($amount > $user_balance) {
            $sender->sendMessage((string) new Message(
                "not-enough-money",
                ["{balance}", "{symbol}"],
                [number_format($user_balance), $symbol]
            ));
            return;
        }

        $this->plugin->removeMoney($sender, $amount);
        $this->plugin->addMoney($args[0], $amount);

        $sender->sendMessage((string) new Message(
            "successfully-paid-target",
            ["{name}", "{amount}", "{balance}", "{symbol}"],
            [$args[0], number_format($amount), number_format($user_balance), $symbol]
        ));

        $target = Server::getInstance()->getPlayerExact($args[0]);

        if ($target !== null) {
            $target->sendMessage((string) new Message(
                "target-recieved-money",
                ["{name}", "{amount}", "{symbol}"],
                [$sender->getName(), number_format($amount), $symbol]
            ));
            return;
        }
    }
}