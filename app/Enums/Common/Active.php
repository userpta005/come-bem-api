<?php

namespace App\Enums\Common;

use App\Traits\EnumMethods;

enum Active: int
{
    use EnumMethods;

    case YES = 1;
    case NOT = 2;

    public function name(): string
    {
        return match ($this) {
            static::YES => 'Sim',
            static::NOT => 'Não',
        };
    }
}
