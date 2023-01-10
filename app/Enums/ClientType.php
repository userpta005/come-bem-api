<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum ClientType: int
{
    use EnumMethods;

    case RESPONSIBLE = 1;
    case RESPONSIBLE_DEPENDENT = 2;

    public function name(): string
    {
        return match ($this) {
            static::RESPONSIBLE => 'Responsável',
            static::RESPONSIBLE_DEPENDENT => 'Consumidor Responsável',
        };
    }
}
