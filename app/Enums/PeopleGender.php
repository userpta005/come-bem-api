<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum PeopleGender: string
{
    use EnumMethods;

    case MALE = 'M';
    case FEMALE = 'F';

    public function name(): string
    {
        return match ($this) {
            static::MALE => 'Masculino',
            static::FEMALE => 'Feminino',
        };
    }
}
