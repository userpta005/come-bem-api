<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ncm extends Model
{
    protected $fillable = [
        'code', 'category_id', 'ipi', 'dt_start',
        'dt_end', 'um_id', 'description', 'gtinp',
        'gtinh',
    ];

    public function um()
    {
        return $this->belongsTo(MeasurementUnit::class, 'um_id');
    }

    public function category()
    {
        return $this->belongsTo(CategoryNcm::class, 'category_id');
    }
}
