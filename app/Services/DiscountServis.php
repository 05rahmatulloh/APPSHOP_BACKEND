<?php

namespace App\Services;

use App\Models\Discounts;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

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
            throw new Exception('Kode diskon tidak valid');
        }

        /**
         * ============================
         * DISKON PRODUK (scope=product)
         * ============================
         */
        if ($discount->scope === 'product') {

            if (!$discount->products()
                ->where('product_id', $product->id)
                ->exists()) {
                throw new Exception('Diskon tidak berlaku untuk produk ini');
            }

            $finalPrice = match ($discount->type) {
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
                'original_price' => $product->price,
                'final_price' => $finalPrice,
                'discount_type' => $discount->type,
                'discount_value' => $discount->value,
                'free_shipping' => false,
            ];
        }

        /**
         * ============================
         * FREE SHIPPING (scope=order)
         * ============================
         */
        if ($discount->scope === 'order' && $discount->type === 'free_shipping') {
            return [
                'original_price' => $product->price,
                'final_price' => $product->price, // TIDAK BOLEH DIUBAH
                'discount_type' => 'free_shipping',
                'discount_value' => 0,
                'free_shipping' => true,
            ];
        }

        throw new Exception('Tipe diskon tidak dikenali');
    }

    public function decreaseStock(Discounts $discounts, int $qty = 1): void
    {
        DB::transaction(fn () =>
            $discounts->decrement('stock', $qty)
        );
    }
}
