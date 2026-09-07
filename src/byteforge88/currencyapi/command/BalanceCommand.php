<?php

declare(strict_types=1);

namespace byteforge88\currencyapi\command;

use pocketmine\command\CommandSender;

use pocketmine\Player;

use byteforge88\currencyapi\command\utils\CommandDetails;

use byteforge88\currencyapi\CurrencyAPI;

class BalanceCommand extends CurrencyCommand {

    public function __construct(protected CurrencyAPI $plugin) {
        parent::__construct(CommandDetails::COMMAND_NAME_BAL, $this->plugin);
        $this->setDescription(CommandDetails::COMMAND_DESC_BAL);
        $this->setAliases(CommandDetails::COMMAND_ALIASES_BAL);
        $this->setPermission(CommandDetails::COMMAND_PERMISSION_BAL);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage((string) new Message("use-command-ingame"));
            return;
        }

        $c = CurrencyAPI::getInstance();
        $symbol = $c->getConfig()->get("currency-symbol");
        $balance = $c->getBalance($sender);
        $f_balance = $c->formatMoney($balance);

        $sender->sendMessage((string) new Message("user-balance", ["{balance}", {symbol}], [$f_balance, $symbol]));
    }
}