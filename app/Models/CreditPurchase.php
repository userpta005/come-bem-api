<?php

namespace App\Models;

use App\Enums\CreditPurchaseStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class CreditPurchase extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => CreditPurchaseStatus::class,
        'checkout' => 'array',
    ];

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

        if (in_array('created_at', $attributes)) {
            $guarded[] = 'created_at';
        }
        if (in_array('updated_at', $attributes)) {
            $guarded[] = 'updated_at';
        }


        $this->guarded = array_merge($guarded, $this->guarded);
    }
}
