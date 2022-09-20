<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum SettingsStatus: string
{
    use EnumMethods;

    case ENABLED = 'H';
    case DENIED = 'B';
    case CANCELED = 'C';
    case SUSPENDED = 'S';

    public function name(): string
    {
        return match ($this) {
            static::ENABLED => 'Habilitado',
            static::DENIED => 'Bloqueado',
            static::SUSPENDED => 'Suspenso',
            static::CANCELED => 'Cancelado',
        };
    }
}
