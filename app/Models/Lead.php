<?php

namespace App\Models;

use App\Enums\LeadStatus;
use App\Traits\DefaultAccessors;
use App\Traits\ScopePerson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory, ScopePerson, DefaultAccessors;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => LeadStatus::class,
    ];
}
