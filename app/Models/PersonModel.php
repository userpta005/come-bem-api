<?php

namespace App\Models;

use App\Enums\Common\Status;
use App\Enums\PeopleGender;
use App\Traits\DefaultAccessors;
use App\Traits\ScopePerson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

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

        $attributes = Schema::getColumnListing($this->getTable());
        $guarded = ['id'];
        $casts = [];
        $appends = ['info'];

        if (in_array('created_at', $attributes)) {
            $guarded[] = 'created_at';
        }
        if (in_array('updated_at', $attributes)) {
            $guarded[] = 'updated_at';
        }
        if (in_array('status', $attributes)) {
            $casts['status'] = Status::class;
        }
        if (in_array('gender', $attributes)) {
            $casts['gender'] = PeopleGender::class;
        }

        $this->guarded = array_merge($guarded, $this->guarded);
        $this->casts = array_merge($casts, $this->casts);
        $this->appends = array_merge($appends, $this->appends);
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
