<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeasurementUnit extends CommonModel
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
}
