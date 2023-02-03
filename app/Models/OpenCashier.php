<?php

namespace App\Models;

use App\Models\User;
use App\Models\Store;
use App\Models\Cashier;
use App\Models\CashMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpenCashier extends Model
{
    use HasFactory;

    protected $fillable = [
        'cashier_id', 'user_id',
        'operation', 'token', 'balance',
        'date_operation', 'store_id'
    ];

    public static function operations($option = null)
    {
        $options = [
            1 => 'Abertura',
            'Fechamento',
            'Inativamento',
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

    /**
     * Get all of the cashMovements for the OpenCashier
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cashMovements(): HasMany
    {
        return $this->hasMany(CashMovement::class, 'open_cashier_id');
    }
}
