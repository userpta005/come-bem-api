<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum AccountTurn: int
{
    use EnumMethods;

    case MORNING = 1;
    case AFTERNOON = 2;
    case NOCTURNAL = 3;

    public function name(): string
    {
        return match ($this) {
            static::MORNING => 'Matutino',
            static::AFTERNOON => 'Vespertino',
            static::NOCTURNAL => 'Noturno',
        };
    }
}
