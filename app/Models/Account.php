<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    protected $casts = [];

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
        'attr_daily_limit'
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
     * Get all of the cards for the Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    /**
     * Get all of the limitedProducts for the Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function limitedProducts(): HasMany
    {
        return $this->hasMany(Comment::class, 'foreign_key', 'local_key');
    }
}
