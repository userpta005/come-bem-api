<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum TenantSignature: int
{
    use EnumMethods;

    case TRIAL = 1;
    case MONTHLY = 2;
    case QUARTERLY = 3;
    case SEMI_ANNUAL = 4;
    case YEARLY = 5;
    case BIANAL = 6;

    public function name(): string
    {
        return match ($this) {
            static::TRIAL => 'Trial',
            static::MONTHLY => 'Mensal',
            static::QUARTERLY => 'Trimestral',
            static::SEMI_ANNUAL => 'Semestral',
            static::YEARLY => 'Anual',
            static::BIANAL => 'Bianal'
        };
    }
}
