<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum StoreStatus: int
{
    use EnumMethods;

    case ENABLED = 1;
    case BLOCKED = 2;
    case CANCELED = 4;

    public function name(): string
    {
        return match ($this) {
            static::ENABLED => 'Habilitado',
            static::BLOCKED => 'Bloqueado',
            static::CANCELED => 'Cancelado'
        };
    }
}
