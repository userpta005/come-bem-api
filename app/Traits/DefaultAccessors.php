<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait DefaultAccessors
{
    public function info(): Attribute
    {
        return new Attribute(
            get: fn () => ($this->name ?? $this->people->name) .
                (($this->nif ?? ($this->people->nif ?? null)) ? (' - ' . ($this->nif ?? $this->people->nif)) : '')
        );
    }

    public function nif(): Attribute
    {
        return new Attribute(
            get: fn ($value) => !empty($value) ? nifMask($value) : (!empty($this->people->nif) ? $this->people->nif : '')
        );
    }

    public function phone(): Attribute
    {
        return new Attribute(
            get: fn ($value) => !empty($value) ? nifMask($value) : (!empty($this->people->phone) ? $this->people->phone : '')
        );
    }

    public function zipCode(): Attribute
    {
        return new Attribute(
            get: fn ($value) => !empty($value) ? nifMask($value) : (!empty($this->people->zip_code) ? $this->people->zip_code : '')
        );
    }
}
