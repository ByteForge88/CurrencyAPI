<?php

declare(strict_types=1);

namespace byteforge88\currencyapi\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

use pocketmine\network\mcpe\protocol\types\CommandEnum;
use pocketmine\network\mcpe\protocol\types\CommandParameter;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

use pocketmine\Player;

use byteforge88\currencyapi\CurrencyAPI;

use byteforge88\currencyapi\api\Currency;

use byteforge88\currencyapi\database\Database;

use byteforge88\currency\utils\Permission;

class BalanceCommand extends CurrencyCommand {

    public function __construct(protected CurrencyAPI $plugin) {
        parent::__construct("balance", $this->plugin);
        $this->setDescription("Check out your current balance");
        $this->setAliases(["bal"]);
        $this->setUsage("/balance <type: string");
        $this->setPermission(Permission::BALANCE_PERMISSION);
        $currencyTypes = Database::getInstance()->fetchCurrencyType();
        $this->setParameter(new CommandParameter(
            "type",
            AvailableCommandsPacket::ARG_TYPE_STRING,
            false,
            new CommandEnum("type", $currencyTypes)
        ), 0, 0);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("Run this command in-game!");
            return;
        }

        if (isset($args[0])) {
            throw new InvalidCommandSyntaxException();
        }

        $database = Database::getInstance();

        if (!$database->isCurrencyType($args[0])) {
            $sender->sendMessage();
            return;
        }

        //$balance = CurrencyAPI::getInstance()->getBalance($sender, "money");
    }
}