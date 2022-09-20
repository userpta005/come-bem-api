<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum BannerType: int
{
    use EnumMethods;

    case ADVERTISING = 1;

    public function name(): string
    {
        return match ($this) {
            static::ADVERTISING => 'Propaganda',
        };
    }
}
