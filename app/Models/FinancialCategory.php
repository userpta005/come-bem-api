<?php

namespace App\Models;

use App\Enums\FinancialCategoryClass;
use App\Enums\FinancialCategoryType;
use App\Models\CommonModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kalnoy\Nestedset\NodeTrait;

class FinancialCategory extends CommonModel
{
    use NodeTrait, HasFactory;

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
        'type' => FinancialCategoryType::class,
        'class' => FinancialCategoryClass::class
    ];

    public function info(): Attribute
    {
        return new Attribute(
            get: fn () => $this->code . ' - ' . $this->description,
        );
    }
}
