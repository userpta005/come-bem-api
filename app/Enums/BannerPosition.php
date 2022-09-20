<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum BannerPosition: int
{
    use EnumMethods;

    case TOP = 1;
    case RIGHT_CORNER = 2;
    case PARTNERS = 3;
    case GALLERY_HOME = 4;

    public function name(): string
    {
        return match ($this) {
            static::TOP => 'Portal (Topo)',
            static::RIGHT_CORNER => 'Portal (Canto Direito)',
            static::PARTNERS => 'Portal (Parceiros)',
            static::GALLERY_HOME => 'Portal (Galeria:Home)',
        };
    }
}
