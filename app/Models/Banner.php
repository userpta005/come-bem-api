<?php

namespace App\Models;

use App\Enums\BannerPosition;
use App\Enums\BannerType;
use App\Enums\Common\Active;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => Active::class,
        'position' => BannerPosition::class,
        'type' => BannerType::class
    ];

    /**
     * Get the banners image.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function image(): Attribute
    {
        return new Attribute(
            get: fn ($value) => asset('storage/' . $value)
        );
    }
}
