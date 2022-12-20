<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum AccountEntryType: int
{
    use EnumMethods;

    case CREDIT = 1;
    case CONSUMPTION = 2;

    public function name(): string
    {
        return match ($this) {
            static::CREDIT => 'Crédito',
            static::CONSUMPTION => 'Consumo',
        };
    }
}
