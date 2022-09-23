<?php

namespace App\Models;

use App\Enums\Common\Status;
use Illuminate\Database\Eloquent\Model;

abstract class CommonModel extends Model
{
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
}
