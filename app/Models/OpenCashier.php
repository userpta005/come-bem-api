<?php

namespace App\Models;

use App\Models\User;
use App\Models\Store;
use App\Models\Cashier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpenCashier extends Model
{
    use HasFactory;

    protected $fillable = [
        'cashier_id', 'user_id',
        'operation', 'token', 'balance',
        'money_change', 'date_operation', 'store_id'
    ];

    public static function operations($option = null)
    {
        $options = [
            1 => 'Abertura',
            'Fechamento',
            'Inativa',
        ];

        if (!$option) {
            return $options;
        }

        return $options[$option];
    }

    /**
     * Get the cashier that owns the OpenCashier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(Cashier::class, 'cashier_id');
    }

    /**
     * Get the user that owns the OpenCashier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the store that owns the OpenCashier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
