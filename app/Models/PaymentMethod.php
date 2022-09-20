<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentMethod extends Model
{
    const BOLETO = 1;
    const CREDIT_CARD = 2;
    const SPOT = 6;
    const PIX = 4;

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'payment_methods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'icon', 'description', 'is_enabled', 'code'
    ];

    public function getInfoAttribute()
    {
        return $this->description;
    }

    /**
     * Get the banners icon.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function icon(): Attribute
    {
        return new Attribute(
            get: fn ($value) => asset('storage/' . $value)
        );
    }
}
