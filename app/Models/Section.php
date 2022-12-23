<?php

namespace App\Models;

use App\Enums\SectionType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kalnoy\Nestedset\NodeTrait;

class Section extends CommonModel
{
    use HasFactory, NodeTrait;

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
        'type' => SectionType::class,
    ];

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
