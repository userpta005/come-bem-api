<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum TenantDueDays: int
{
    use EnumMethods;

    case FIVE = 1;
    case TEN = 2;
    case FIFTEEN = 3;

    public function name(): string
    {
        return match ($this) {
            static::FIVE => '5',
            static::TEN => '10',
            static::FIFTEEN => '15',
        };
    }
}
