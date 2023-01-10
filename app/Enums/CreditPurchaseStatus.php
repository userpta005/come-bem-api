<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum CreditPurchaseStatus: int
{
    use EnumMethods;

    case PENDING = 1;
    case PAYED = 2;
    case CANCELED = 3;

    public function name(): string
    {
        return match ($this) {
            static::PENDING => 'Pendente',
            static::PAYED => 'Pago',
            static::CANCELED => 'Cancelado',
        };
    }
}
