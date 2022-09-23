<?php

namespace App\Models;

use App\Enums\ParameterType;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parameter extends CommonModel
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
        'type' => ParameterType::class
    ];
}
