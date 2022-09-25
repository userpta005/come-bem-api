<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum SectionType: string
{
    use EnumMethods;

    case SYNTHETIC = 'S';
    case ANALYTICAL = 'A';

    public function name(): string
    {
        return match ($this) {
            static::SYNTHETIC => 'Sintética',
            static::ANALYTICAL => 'Análitica',
        };
    }
}
