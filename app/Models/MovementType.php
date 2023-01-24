<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'class',
    ];

    public static function classOption($option = null)
    {
        $options = [
            1 => 'Entrada',
            'Saída',
        ];

        if (!$option) {
            return $options;
        }

        return $options[$option];
    }
}
