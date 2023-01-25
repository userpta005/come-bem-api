<?php

namespace App\Models;

use App\Models\Store;
use App\Models\Client;
use App\Models\Cashier;
use App\Models\MovementType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'cashier_id', 'client_id',
        'store_id', 'token', 'movement_type_id',
        'payment_method_id', 'amount', 'date_operation'
    ];

    /**
     * Get the cashier that owns the CashMovement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(Cashier::class, 'cashier_id');
    }

    /**
     * Get the client that owns the CashMovement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the store that owns the CashMovement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the movement_type that owns the CashMovement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function movementType(): BelongsTo
    {
        return $this->belongsTo(MovementType::class, 'movement_type_id');
    }

    /**
     * Get the payment_method that owns the CashMovement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
