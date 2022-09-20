<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum ParameterType: int
{
    use EnumMethods;

    case ALPHANUMERIC = 1;
    case NUMERIC = 2;
    case LOGICAL = 3;

    public function name(): string
    {
        return match ($this) {
            static::ALPHANUMERIC => 'Alfanumérico',
            static::NUMERIC => 'Numérico',
            static::LOGICAL => 'Lógico',
        };
    }
}
