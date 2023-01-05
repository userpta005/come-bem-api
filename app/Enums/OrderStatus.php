<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum OrderStatus: int
{
    use EnumMethods;

    case OPENED = 1;
    case RETIRED = 2;

    public function name(): string
    {
        return match ($this) {
            static::OPENED => 'Não retirado',
            static::RETIRED => 'Entregue'
        };
    }

    public static function colors(): array
    {
        return [
            1  => '#ffd600',
            2 => '#2dce89',
        ];
    }
}
