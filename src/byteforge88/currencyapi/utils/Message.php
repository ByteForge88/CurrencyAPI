<?php

declare(strict_types=1);

namespace byteforge88\currencyapi\utils;

use pocketmine\utils\TextFormat;

use byteforge88\currencyapi\CurrencyAPI;

class Message {
    
    private string $message;
    
    public function __construct(
        string $key,
        array|string|null $tags = null,
        array|string|null $replacements = null
    ) {
        $msg = CurrencyAPI::getInstance()->messages->get($key);

        if ($tags !== null && $replacements !== null) {
            $tags = (array) $tags;
            $replacements = (array) $replacements;

            $msg = str_replace($tags, $replacements, $msg);
        }

        $this->message = TextFormat::colorize($msg);
    }

    public function __toString() : string{
        return $this->message;
    }
}