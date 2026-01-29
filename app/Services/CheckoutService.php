<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ongkir;
use App\Services\DiscountService;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    protected DiscountService $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    /**
     * Ambil ongkir
     */
    private function getShippingCost(string $kampus): array
    {
        $ongkir = Ongkir::where('kampus', $kampus)->first();

        if (!$ongkir) {
            return [
                'success' => false,
                'message' => "Ongkir untuk {$kampus} tidak ditemukan",
                'cost' => 0
            ];
        }

        return [
            'success' => true,
            'cost' => $ongkir->biaya
        ];
    }

    /**
     * =====================
     * PREVIEW CHECKOUT
     * =====================
     */
    public function preview($user, array $data): array
    {
        $cart = Cart::with('items.product')
            ->where('user_id', $user->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Cart kosong'
            ];
        }

        $items = [];
        $subtotal = 0;
        $discountTotal = 0;
        $freeShipping = false;

        foreach ($cart->items as $item) {
            $product = $item->product;

            if ($product->stock < $item->quantity) {
                return [
                    'success' => false,
                    'message' => "Stok {$product->name} tidak cukup"
                ];
            }

            $price = $product->price;
            $finalPrice = $price;

            if (!empty($data['discount_code'])) {
                $result = $this->discountService->apply(
                    $product,
                    $data['discount_code']
                );

                if (!$result['success']) {
                    return $result;
                }

                if ($result['data']['free_shipping']) {
                    $freeShipping = true;
                }

                if ($result['data']['discount_type'] !== 'free_shipping') {
                    $finalPrice = $result['data']['final_price'];
                    $discountTotal +=
                        ($price - $finalPrice) * $item->quantity;
                }
            }

            $subtotal += $price * $item->quantity;

            $items[] = [
                'product_id'  => $product->id,
                'name'        => $product->name,
                'price'       => $price,
                'final_price' => $finalPrice,
                'quantity'    => $item->quantity,
                'subtotal'    => $finalPrice * $item->quantity,
                'Data Discount' => $result['data'] ?? null,
            ];
        }

        $shipping = $freeShipping
            ? ['success' => true, 'cost' => 0]
            : $this->getShippingCost($data['shipping_address']);

        if (!$shipping['success']) {
            return $shipping;
        }

        return [
            'success' => true,
            'items' => $items,
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'shipping_cost' => $shipping['cost'],
            'free_shipping' => $freeShipping,
            'total' => max($subtotal - $discountTotal + $shipping['cost'], 0),
        ];
    }

    /**
     * =====================
     * CHECKOUT FINAL
     * =====================
     */
    public function checkout($user, array $data): array
    {
        return DB::transaction(function () use ($user, $data) {

            $preview = $this->preview($user, $data);

            if (!$preview['success']) {
                return $preview;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'order_code' => 'ORD-' . now()->timestamp,
                'subtotal' => $preview['subtotal'],
                'discount_total' => $preview['discount_total'],
                'shipping_cost' => $preview['shipping_cost'],
                'total' => $preview['total'],
                'status' => 'pending',
                'payment_method' => $data['payment_method'],
                'shipping_address' => $data['shipping_address'],
            ]);

            $cart = Cart::with('items.product')
                ->where('user_id', $user->id)
                ->first();

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->product->price * $item->quantity,
                ]);
            }

            return [
                'success' => true,
                'order' => $order
            ];
        });
    }
}
