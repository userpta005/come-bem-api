<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait DefaultAccessors
{
    public function info(): Attribute
    {
        return new Attribute(
            get: fn () => $this->name . ' - ' . $this->nif
        );
    }

    public function nif(): Attribute
    {
        return new Attribute(
            get: fn ($value) => nifMask($value)
        );
    }

    public function phone(): Attribute
    {
        return new Attribute(
            get: fn ($value) => phoneMask($value)
        );
    }

    public function zipCode(): Attribute
    {
        return new Attribute(
            get: fn ($value) => zipCodeMask($value)
        );
    }
}
