<?php

declare(strict_types=1);

namespace byteforge88\currencyapi\command;

use pocketmine\command\CommandSender;

use pocketmine\Player;

use byteforge88\currencyapi\command\utils\CommandDetails;

use byteforge88\currencyapi\CurrencyAPI;

use byteforge88\currencyapi\utils\Message;

class TopBalanceCommand extends CurrencyCommand {

    public function __construct(protected CurrencyAPI $plugin) {
        parent::__construct(CommandDetails::COMMAND_NAME_TOPBAL, $this->plugin);
        $this->setDescription(CommandDetails::COMMAND_DESC_TOPBAL);
        $this->setAliases(CommandDetails::COMMAND_ALIASES_TOPBAL);
        $this->setPermission(CommandDetails::COMMAND_PERMISSION_TOPBAL);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage((string) new Message("use-command-ingame"));
            return;
        }

        $config = $this->plugin->getConfig();
        $limit = $config->get("leaderboard-limit");
        $symbol = $config->get("currency-symbol");
        $top_bal = $this->plugin->getTopBalances($limit);
        $i = 1;

        $sender->sendMessage((string) new Message("leaderboard-header", ["{limit}"], [$limit]));

        foreach ($top_bal as $data) {
            $sender->sendMessage((string) new Message(
                "leaderboard-body",
                ["{position}", "{name}", "{balance}", "{symbol}"],
                [$i, $data["user"], number_format($data["balance"]), $symbol]
            ));
            $i++;
        }
    }
}