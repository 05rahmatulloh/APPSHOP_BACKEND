<?php
namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\DiscountService;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckoutService
{
    protected DiscountService $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function checkout($user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {

            $cart = Cart::with('items.product')
                ->where('user_id', $user->id)
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                throw new Exception('Cart kosong');
            }

            $subtotal = 0;
            $discountTotal = 0;

            foreach ($cart->items as $item) {
                $product = $item->product;

                if ($product->stock < $item->quantity) {
                    throw new Exception("Stok {$product->name} tidak cukup");
                }

                $subtotal += $product->price * $item->quantity;
            }

            // 🔥 GUNAKAN DISCOUNTSERVICE
            if (!empty($data['discount_code'])) {
                foreach ($cart->items as $item) {
                    $result = $this->discountService->apply(
                        $item->product,
                        $data['discount_code']
                    );

                    $discountTotal +=
                        ($item->product->price - $result['final_price'])
                        * $item->quantity;
                }
            }

            $shippingCost = $data['shipping_cost'] ?? 0;
            $total = max($subtotal - $discountTotal + $shippingCost, 0);

            // CREATE ORDER
            $order = Order::create([
                'user_id' => $user->id,
                'order_code' => 'ORD-' . now()->timestamp,
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $data['payment_method'],
                'shipping_address' => $data['shipping_address'],
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->product->price * $item->quantity,
                ]);
            }

            return $order;
        });
    }




    public function preview($user, array $data)
{
    $cart = Cart::with('items.product')
        ->where('user_id', $user->id)
        ->first();

    if (!$cart || $cart->items->isEmpty()) {
        throw new Exception('Cart kosong');
    }

    $items = [];
    $subtotal = 0;
    $discountTotal = 0;

    foreach ($cart->items as $item) {
        $product = $item->product;

        if ($product->stock < $item->quantity) {
            throw new Exception("Stok {$product->name} tidak cukup");
        }

        $price = $product->price;
        $finalPrice = $price;

        // 🔥 apply discount (jika ada)
        if (!empty($data['discount_code'])) {
            $result = $this->discountService->apply(
                $product,
                $data['discount_code']
            );

            $finalPrice = $result['final_price'];
            $discountTotal +=
                ($price - $finalPrice) * $item->quantity;
        }

        $lineSubtotal = $finalPrice * $item->quantity;
        $subtotal += $price * $item->quantity;

        $items[] = [
            'product_id'   => $product->id,
            'name'         => $product->name,
            'price'        => $price,
            'final_price'  => $finalPrice,
            'quantity'     => $item->quantity,
            'subtotal'     => $lineSubtotal,
        ];
    }

    $shippingCost = $data['shipping_cost'] ?? 0;
    $total = max($subtotal - $discountTotal + $shippingCost, 0);

    return [
        'items'          => $items,
        'subtotal'       => $subtotal,
        'discount_total' => $discountTotal,
        'shipping_cost'  => $shippingCost,
        'total'          => $total,
    ];
}

}
