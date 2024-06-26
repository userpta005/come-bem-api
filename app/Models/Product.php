<?php

namespace App\Models;

use App\Enums\NutritionalClassification;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends CommonModel
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
        'nutritional_classification' => NutritionalClassification::class,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image_url',
    ];

    /**
     * Get the product image url.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function imageUrl(): Attribute
    {
        return new Attribute(
            get: fn () => $this->image ? asset('storage/' . $this->image) : asset('images/noimage.png')

        );
    }

    /**
     * Get the ncm that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ncm(): BelongsTo
    {
        return $this->belongsTo(Ncm::class);
    }

    /**
     * Get the section that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the store that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the um that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function um(): BelongsTo
    {
        return $this->belongsTo(MeasurementUnit::class, 'um_id');
    }

    /**
     * The limitedProducts that belong to the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function limitedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class);
    }

    /**
     * Get the stock associated with the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }
}
