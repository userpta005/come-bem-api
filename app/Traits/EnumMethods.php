<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait EnumMethods
{
    public static function all(): Collection
    {
        $enums = collect();
        foreach (self::cases() as $enum) {
            $enums->put($enum->value, $enum->name());
        }

        return $enums;
    }

    public static function only(array $types): Collection
    {
        $enums = collect();
        foreach (self::cases() as $enum) {
            if (in_array($enum->value, $types)) {
                $enums->put($enum->value, $enum->name());
            }
        }

        return $enums;
    }

    public static function getEnumByLabel(string $label): self | null
    {
        $all = self::all();
        return self::tryFrom($all->search($label));
    }
}
