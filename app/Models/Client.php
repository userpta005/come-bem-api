<?php

namespace App\Models;

use App\Enums\ClientType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends PersonModel
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
        'type' => ClientType::class
    ];

    /**
     * Get all of the dependents for the Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dependents(): HasMany
    {
        return $this->hasMany(Dependent::class);
    }
}
