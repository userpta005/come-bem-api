<?php

namespace App\Models;

use App\Enums\AccountTurn;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends CommonModel
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
    protected $casts = [
        'turn' => AccountTurn::class,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'attr_created_at',
        'attr_updated_at',
        'attr_status',
        'attr_balance',
        'attr_daily_limit',
        'day_balance'
    ];

    /**
     * Get balance converted to Brazilian currency.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function attrBalance(): Attribute
    {
        return new Attribute(
            get: fn () => floatToMoney($this->balance),
        );
    }

    /**
     * Get daily_limit converted to Brazilian currency.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function attrDailyLimit(): Attribute
    {
        return new Attribute(
            get: fn () => floatToMoney($this->daily_limit),
        );
    }

    /**
     * Get the status name of the enum.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function attrStatus(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status->name(),
        );
    }

    /**
     * Get created_at converted to Brazilian format .
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function attrCreatedAt(): Attribute
    {
        return new Attribute(
            get: fn () => $this->created_at->format('d/m/Y')
        );
    }

    /**
     * Get updated_at converted to Brazilian format .
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function attrUpdatedAt(): Attribute
    {
        return new Attribute(
            get: fn () => $this->updated_at->format('d/m/Y')
        );
    }

    public function dayBalance(): Attribute
    {
        return new Attribute(get: function () {
            if (empty(floatval($this->daily_limit)) || ($this->orders && $this->orders->isEmpty())) {
                return $this->daily_limit;
            }
            $sum = $this->orders->filter(fn ($item) => $item->date == today()->format('Y-m-d'))->sum('amount');
            return $this->daily_limit - $sum;
        });
    }

    public function dayBalanceByDate($date)
    {
        if (empty(floatval($this->daily_limit)) || ($this->orders && $this->orders->isEmpty())) {
            return $this->daily_limit;
        }
        $sum = $this->orders->filter(fn ($item) => $item->date == carbon($date)->format('Y-m-d'))->sum('amount');
        return $this->daily_limit - $sum;
    }

    /**
     * Get the dependent that owns the Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dependent(): BelongsTo
    {
        return $this->belongsTo(Dependent::class);
    }

    /**
     * Get the store that owns the Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get all of the cards for the Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    /**
     * The Product that belong to the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function limitedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Get all of the accountEntries for the Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accountEntries(): HasMany
    {
        return $this->hasMany(AccountEntry::class);
    }

    /**
     * Get all of the orders for the Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
