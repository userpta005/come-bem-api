<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    const REQUISITION = 1;
    const DEVOLUTION = 2;
    const SELL = 3;
    const TRANSFER = 4;

    protected $fillable = [
        'code',
        'type',
        'user_id',
        'stock_id',
        'quantity',
    ];

    public static function types($option = null)
    {
        $options = [
            self::REQUISITION => 'Requisição',
            self::DEVOLUTION => 'Devolução',
            self::TRANSFER => 'Transferência',
            self::SELL => 'Venda',
        ];

        if (!$option)
            return $options;

        return $options[$option];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
