<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum StockMovementType: int
{
    use EnumMethods;

    case REQUISITION = 1;
    case DEVOLUTION = 2;
    case SELL = 3;
    case TRANSFER = 4;

    public function name(): string
    {
        return match ($this) {
            static::REQUISITION => 'Requisição',
            static::DEVOLUTION => 'Devolução',
            static::SELL => 'Transferência',
            static::TRANSFER => 'Venda'
        };
    }
}
