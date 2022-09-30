<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum FinancialCategoryClass: int
{
    use EnumMethods;

    case SYNTHETIC = 1;
    case ANALYTICAL = 2;

    public function name(): string
    {
        return match ($this) {
            static::SYNTHETIC => 'Sintética',
            static::ANALYTICAL => 'Análitica',
        };
    }
}
