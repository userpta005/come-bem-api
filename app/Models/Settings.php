<?php

namespace App\Models;

use App\Enums\SettingsStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Settings extends CommonModel
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
        'status' => SettingsStatus::class
    ];

    /**
     * Get the setting logo url.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function logoUrl(): Attribute
    {
        return new Attribute(
            get: fn () => asset('storage/' . $this->logo)
        );
    }

    /**
     * Get the city that owns the Settings
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the store that owns the Settings
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
