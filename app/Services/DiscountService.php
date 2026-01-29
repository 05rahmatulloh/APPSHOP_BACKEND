<?php

namespace App\Services;

use App\Models\Discounts;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DiscountService
{
    public function apply(Product $product, string $code): array
    {
        $discount = Discounts::where('code', $code)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where(function ($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->first();

        if (!$discount) {
            return [
                'success' => false,
                'message' => 'Kode diskon tidak valid',
                'data' => null
            ];
        }

        /**
         * ============================
         * DISKON PRODUK
         * ============================
         */
        if ($discount->scope === 'product') {

            if (!$discount->products()
                ->where('product_id', $product->id)
                ->exists()) {

                return [
                    'success' => true,
                    'message' => 'Produk tidak termasuk diskon',
                    'data' => [
                        'original_price' => $product->price,
                        'final_price' => (float)$product->price,
                        'discount_type' => null,
                        'discount_value' => 0,
                        'free_shipping' => false,
                    ]
                ];
            }

            $finalPrice = (float)match ($discount->type) {
                'percentage' => max(
                    $product->price - ($product->price * $discount->value / 100),
                    0
                ),
                'nominal' => max(
                    $product->price - $discount->value,
                    0
                ),
                default => $product->price,
            };

            return [
                'success' => true,
                'message' => 'Diskon berhasil diterapkan',
                'data' => [
                    'original_price' => $product->price,
                    'final_price' => $finalPrice,
                    'discount_type' => $discount->type,
                    'discount_value' => $discount->value,
                    'free_shipping' => false,
                ]
            ];
        }

        /**
         * ============================
         * FREE SHIPPING
         * ============================
         */
        if ($discount->scope === 'order' && $discount->type === 'free_shipping') {
            return [
                'success' => true,
                'message' => 'Gratis ongkir aktif',
                'data' => [
                    'original_price' => $product->price,
                    'final_price' => $product->price,
                    'discount_type' => 'free_shipping',
                    'discount_value' => 0,
                    'free_shipping' => true,
                ]
            ];
        }

        return [
            'success' => false,
            'message' => 'Tipe diskon tidak dikenali',
            'data' => null
        ];
    }

    public function decreaseStock(Discounts $discounts, int $qty = 1): void
    {
        DB::transaction(fn () =>
            $discounts->decrement('stock', $qty)
        );
    }
}
