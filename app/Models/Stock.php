<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'product_id',
        'store_id',
        'quantity',
        'provider_lot',
        'lot',
        'expiration_date'
    ];

    protected $dates = ['expiration_date'];

    public function getInfoAttribute()
    {
        if ($this->lot)
            return $this->product->name . ' - Lote: ' . $this->provider_lot;

        return $this->product->name;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
