<?php

declare(strict_types=1);

namespace byteforge88\currencyapi\utils;

class Permission {

    public const BALANCE_PERMISSION = "currencyapi.balance";
    public const OTHER_BALANCE_PERMISSION = self::BALANCE_PERMISSION;
    public const PAY_MONEY_PERMISSION = "currencyapi.paymoney";
    public const ADD_MONEY_PERMISSION = "currencyapi.addmoney";
    public const SET_MONEY_PERMISSION = "currencyapi.setmoney";
    public const REMOVE_MONEY_PERMISSION = "currencyapi.removemoney";
    
    private function __construct() {
        //noop
    }
}