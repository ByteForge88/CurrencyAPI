<?php

declare(strict_types=1);

namespace byteforge88\currencyapi\command\utils;

class CommandDetails {

    // BalanceCommand.php
    public const COMMAND_NAME_BAL = "balance";
    public const COMMAND_DESC_BAL = "View your current balance";
    public const COMMAND_USAGE_BAL = "/balance";
    public const COMMAND_ALIASES_BAL = ["bal"];
    public const COMMAND_PERMISSION_BAL = "currencyapi.balance";

    // SeeBalanceCommand.php
    public const COMMAND_NAME_SEEBAL = "seebalance";
    public const COMMAND_DESC_SEEBAL = "Checkout another player's balance";
    public const COMMAND_USAGE_SEEBAL = "/seebalance <target: name>";
    public const COMMAND_ALIASES_SEEBAL = ["seebal"];
    public const COMMAND_PERMISSION_SEEBAL = "currencyapi.seebalance";

}