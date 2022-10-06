<?php

namespace App\Models;

use App\Enums\ClientType;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'attr_created_at',
        'attr_updated_at',
        'attr_status',
        'attr_type',
    ];

    /**
     * Get the type name of the enum.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function attrType(): Attribute
    {
        return new Attribute(
            get: fn () => $this->type->name(),
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
     * Get all of the dependents for the Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dependents(): HasMany
    {
        return $this->hasMany(Dependent::class)->person();
    }
}
