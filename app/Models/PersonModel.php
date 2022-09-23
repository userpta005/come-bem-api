<?php

namespace App\Models;

use App\Enums\Common\Status;
use App\Traits\DefaultAccessors;
use App\Traits\ScopePerson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class PersonModel extends Model
{
    use ScopePerson, DefaultAccessors;

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $guarded = [
            'id',
            'created_at',
            'updated_at'
        ];

        $this->guarded = array_merge($guarded, $this->guarded);

        $casts = [
            'status' => Status::class,
        ];

        $this->casts = array_merge($casts, $this->casts);
    }

    /**
     * Get the people that owns the people
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function people(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
