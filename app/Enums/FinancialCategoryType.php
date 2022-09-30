<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum FinancialCategoryType: int
{
    use EnumMethods;

    case EXISTS = 1;
    case BOTH = 2;
    case INPUTS = 3;

    public function name(): string
    {
        return match ($this) {
            static::EXISTS => 'Saídas',
            static::BOTH => 'Ambos',
            static::INPUTS => 'Entradas',
        };
    }
}
