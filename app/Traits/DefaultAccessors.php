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
            get: fn ($value) => ($value ?? ($this->people->nif ?? null)) ? nifMask($value ?? $this->people->nif) : ''
        );
    }

    public function phone(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ($value ?? ($this->people->phone ?? null)) ? phoneMask($value ?? $this->people->phone) : ''
        );
    }

    public function zipCode(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ($value ?? ($this->people->zip_code ?? null)) ? zipCodeMask($value ?? $this->people->zip_code) : ''
        );
    }
}
