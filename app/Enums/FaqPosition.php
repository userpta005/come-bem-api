<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum FaqPosition: int
{
    use EnumMethods;

    case ABOUT = 1;
    case FOOTER = 2;

    public function name(): string
    {
        return match ($this) {
            static::ABOUT => 'Portal (Catálogo - Sobre nós)',
            static::FOOTER => 'Portal (Footer - Duvidas Frequentes)',
        };
    }
}
