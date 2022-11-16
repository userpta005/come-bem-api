<?php

namespace App\Models;

use App\Enums\Common\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

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

        $attributes = Schema::getColumnListing($this->getTable());
        $guarded = ['id'];
        $casts = [];

        if (in_array('created_at', $attributes)) {
            $guarded[] = 'created_at';
        }
        if (in_array('updated_at', $attributes)) {
            $guarded[] = 'updated_at';
        }
        if (in_array('status', $attributes)) {
            $casts['status'] = Status::class;
        }

        $this->guarded = array_merge($guarded, $this->guarded);
        $this->casts = array_merge($casts, $this->casts);
    }
}
