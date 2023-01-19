<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends CommonModel
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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image_url'
    ];

    /**
     * Get the section image url.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function imageUrl(): Attribute
    {
        return new Attribute(
            get: fn () => $this->image ? asset('storage/' . $this->image) : asset('images/noimage.png')

        );
    }
}
