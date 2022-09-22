<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum NutritionalClassification: int
{
    use EnumMethods;

    case UNDER_ANALYSIS = 1;
    case LITTLE_NUTRITIOUS = 2;
    case MODERATE = 3;
    case VERY_NUTRITIOUS = 4;

    public function name(): string
    {
        return match ($this) {
            static::UNDER_ANALYSIS => 'Em Análise',
            static::LITTLE_NUTRITIOUS => 'Pouco Nutritivo',
            static::MODERATE => 'Moderado',
            static::VERY_NUTRITIOUS => 'Muito Nutritivo',
        };
    }
}
