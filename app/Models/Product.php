<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'stock',
        'description',
        'image',
        'is_active',
        'is_cod_available',
        'is_midtrans_available'
    ];

    /* ================= RELATION ================= */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }


public function discounts()
{
return $this->belongsToMany(
Discounts::class, // ✅ model DISCOUNT
'discounts_product', // ✅ pivot table
'product_id', // ✅ FK untuk Product
'discount_id' // ✅ FK untuk Discount
);
}



    /* ================= LOGIC ================= */

    /**
     * Cek apakah produk sedang disewa sekarang
     */
    public function isRentedNow(): bool
    {
        return $this->rentals()
            ->where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->exists();
    }
}
