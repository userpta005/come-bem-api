<?php

namespace App\Models;

use App\Traits\DefaultAccessors;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends CommonModel
{
    use HasFactory, DefaultAccessors;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'info'
    ];

    /**
     * Get the city that owns the Person
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * Get the tenant associated with the Person
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tenant(): HasOne
    {
        return $this->hasOne(Tenant::class);
    }

    /**
     * Get the client associated with the Person
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    /**
     * Get the dependent associated with the Person
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dependent(): HasOne
    {
        return $this->hasOne(Dependent::class);
    }

    /**
     * Get the user associated with the Person
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
