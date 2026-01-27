<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class CartService
 *
 * Menangani seluruh logic bisnis terkait Cart:
 * - Membuat / mengambil cart user
 * - Menambah item ke cart
 * - Update quantity item
 * - Mengosongkan cart
 */
class CartService
{
    /**
     * Ambil cart milik user atau buat baru jika belum ada
     */
    public function getOrCreateCart($user): Cart
    {
        return Cart::firstOrCreate([
            'user_id' => $user->id
        ]);
    }

    /**
     * Menambahkan item ke cart user
     *
     * - Validasi stok
     * - Jika produk sudah ada di cart → quantity & price diperbarui
     * - Jika belum → buat cart item baru
     *
     * @throws Exception
     */
    public function addItem($user, int $productId, int $qty): Cart
    {
        return DB::transaction(function () use ($user, $productId, $qty) {

            $cart = $this->getOrCreateCart($user);

            $product = Product::findOrFail($productId);

            // Validasi stok
            if ($product->stock < $qty) {
                throw new Exception('Stok tidak mencukupi');
            }

            // Cari item di cart
            $item = $cart->items()
                ->where('product_id', $productId)
                ->first();

            if ($item) {
                $newQty = $item->quantity + $qty;

                $item->update([
                    'quantity' => $newQty,
                    'price'    => $product->price * $newQty,
                ]);
            } else {
                $cart->items()->create([
                    'product_id' => $productId,
                    'quantity'   => $qty,
                    'price'      => $product->price * $qty,
                ]);
            }

            // Return cart dengan relasi
            return $cart->load('items.product');
        });
    }

    /**
     * Update quantity item dalam cart
     *
     * - Jika qty <= 0 → item dihapus
     * - Validasi stok produk
     *
     * @throws Exception
     */
    public function updateQty($user, int $itemId, int $qty): void
    {
        DB::transaction(function () use ($user, $itemId, $qty) {

            // Pastikan item milik user yang login
            $item = CartItem::whereHas('cart', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->findOrFail($itemId);

            // Jika quantity 0 atau negatif → hapus item
            if ($qty <= 0) {
                $item->delete();
                return;
            }

            // Validasi stok
            if ($item->product->stock < $qty) {
                throw new Exception('Stok tidak mencukupi');
            }

            // Update quantity & price
            $item->update([
                'quantity' => $qty,
                'price'    => $item->product->price * $qty,
            ]);
        });
    }

    /**
     * Mengosongkan seluruh item dalam cart user
     */
    public function clearCart($user): void
    {
        $cart = Cart::where('user_id', $user->id)->first();

        if ($cart) {
            $cart->items()->delete();
        }
    }
}
