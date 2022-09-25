<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends CommonModel
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the banners icon.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function iconUrl(): Attribute
    {
        return new Attribute(
            get: fn () => asset('storage/' . $this->icon)
        );
    }
}
