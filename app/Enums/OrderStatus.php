<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum OrderStatus: int
{
    use EnumMethods;

    case OPENED = 1;
    case RETIRED = 2;

    public function name(): string
    {
        return match ($this) {
            static::OPENED => 'Em Aberto',
            static::RETIRED => 'Retirado'
        };
    }
}
