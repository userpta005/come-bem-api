<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\MovementClass;

class MovementType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'class',
    ];

    protected $casts = [
        'class' => MovementClass::class
    ];
}
