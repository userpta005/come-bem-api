<?php

namespace App\Models;

use App\Models\OpenCashier;
use App\Models\CashMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cashier extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'code',
        'description', 'status', 'balance', 'open_cashier_id', 'user_id'
    ];

    public static function allStatus($option = null)
    {
        $options = [
            1 => 'Aberto',
            'Fechado',
            'Inativo',
        ];

        if (!$option) {
            return $options;
        }

        return $options[$option];
    }

    public static function types($option = null)
    {
        $options = [
            1 => 'Sintético',
            'Analítico'
        ];

        if (!$option) {
            return $options;
        }

        return $options[$option];
    }

    /**
     * Get the store that owns the Cashier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get all of the cashMovement for the Cashier
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cashMovements(): HasMany
    {
        return $this->hasMany(CashMovement::class);
    }

    /**
     * Get all of the open_cashier for the Cashier
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function openCashiers(): HasMany
    {
        return $this->hasMany(OpenCashier::class);
    }

    /**
     * Get the openCashier that owns the Cashier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function openCashier(): BelongsTo
    {
        return $this->belongsTo(OpenCashier::class);
    }

    /**
     * Get the user that owns the Cashier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
