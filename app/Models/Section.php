<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Section extends Model
{
    use HasFactory, NodeTrait;

    protected $fillable = [
        'name', 'descriptive',
        'type', 'use', 'is_enabled',
        'parent_id', 'order'
    ];

    public static function types($option = null)
    {
        $options =  [
            'S' => 'Sintética',
            'A' => 'Analítica'
        ];

        if (!$option)
            return $options;

        return $options[$option];
    }
}
