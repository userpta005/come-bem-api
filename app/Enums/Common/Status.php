<?php

namespace App\Enums\Common;

use App\Traits\EnumMethods;

enum Status: int
{
    use EnumMethods;

    case ACTIVE = 1;
    case INACTIVE = 2;

    public function name(): string
    {
        return match ($this) {
            static::ACTIVE => 'Ativo',
            static::INACTIVE => 'Inativo',
        };
    }
}
