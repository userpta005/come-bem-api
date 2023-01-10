<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum StoreType: int
{
    use EnumMethods;

    case CANTEEN = 1;
    case KIOSK = 2;

    public function name(): string
    {
        return match ($this) {
            static::CANTEEN => 'Cantina',
            static::KIOSK => 'Quiosque'
        };
    }
}
