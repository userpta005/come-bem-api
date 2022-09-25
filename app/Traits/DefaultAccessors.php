<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait DefaultAccessors
{
    /**
     * Get the people info.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function info(): Attribute
    {
        return new Attribute(
            get: fn () => ($this->name ?? $this->people->name) .
                (($this->nif ?? ($this->people->nif ?? null)) ? (' - ' . ($this->nif ?? $this->people->nif)) : '')
        );
    }

    /**
     * Get the people nif.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function nif(): Attribute
    {
        return new Attribute(
            get: fn ($value) => !empty($value) ? nifMask($value) : (!empty($this->people->nif) ? $this->people->nif : '')
        );
    }

    /**
     * Get the people phone.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function phone(): Attribute
    {
        return new Attribute(
            get: fn ($value) => !empty($value) ? nifMask($value) : (!empty($this->people->phone) ? $this->people->phone : '')
        );
    }

    /**
     * Get the people zipCode.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function zipCode(): Attribute
    {
        return new Attribute(
            get: fn ($value) => !empty($value) ? nifMask($value) : (!empty($this->people->zip_code) ? $this->people->zip_code : '')
        );
    }
}
