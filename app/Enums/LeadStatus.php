<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum LeadStatus: int
{
    use EnumMethods;

    case ACTIVE = 1;
    case BLOCKED = 2;
    case INACTIVE = 3;

    public function name(): string
    {
        return match ($this) {
            static::ACTIVE => 'Ativo',
            static::BLOCKED => 'Bloqueado',
            static::INACTIVE => 'Inativo',
        };
    }
}
