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

namespace byteforge88\currencyapi\command\utils;

class CommandDetails {

    //BalanceCommand.php
    public const COMMAND_NAME_BAL = "balance";
    public const COMMAND_DESC_BAL = "View your current balance";
    public const COMMAND_USAGE_BAL = "/balance";
    public const COMMAND_ALIASES_BAL = ["bal"];
    public const COMMAND_PERMISSION_BAL = "currencyapi.balance";

    //SeeBalanceCommand.php
    public const COMMAND_NAME_SEEBAL = "seebalance";
    public const COMMAND_DESC_SEEBAL = "Checkout another player's balance";
    public const COMMAND_USAGE_SEEBAL = "/seebalance <target: name>";
    public const COMMAND_ALIASES_SEEBAL = ["seebal"];
    public const COMMAND_PERMISSION_SEEBAL = "currencyapi.seebalance";

    //TopBalanceCommand.php
    public const COMMAND_NAME_TOPBAL = "topbalance";
    public const COMMAND_DESC_TOPBAL = "Get a leaderboard with the highest amount of money";
    public const COMMAND_USAGE_TOPBAL = "/topbalance";
    public const COMMAND_ALIASES_TOPBAL = ["topbal"];
    public const COMMAND_PERMISSION_TOPBAL = "currencyapi.topbalance";

    //PayCommand.php
    public const COMMAND_NAME_PAY = "pay";
    public const COMMAND_DESC_PAY = "Pay someone money";
    public const COMMAND_USAGE_PAY = "/pay <target: name> <amount: int>";
    public const COMMAND_ALIASES_PAY = ["paymoney"];
    public const COMMAND_PERMISSION_PAY = "currencyapi.pay";

}