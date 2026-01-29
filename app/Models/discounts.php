<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discounts extends Model
{
    protected $table = 'discounts';

    protected $fillable = [
        'code',
        'scope',        // product | order
        'type',         // percentage | nominal | free_shipping
        'value',
        'stock',
        'is_active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'discounts_product',
            'discount_id',
            'product_id'
        );
    }
}
