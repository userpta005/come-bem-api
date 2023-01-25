<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum MovementClass: string
{
    use EnumMethods;

    case ENTRY = 'E';
    case OUTGOING = 'S';

    public function name(): string
    {
        return match ($this) {
            static::ENTRY => 'Entrada',
            static::OUTGOING => 'Saída'
        };
    }
}
