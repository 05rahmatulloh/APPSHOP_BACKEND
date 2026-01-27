<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discounts extends Model
{
    protected $table = 'discounts';

    protected $fillable = [
    'code',
    'type',
    'value',
    'stock',
    'is_active',
    'start_date',
    'end_date',
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
