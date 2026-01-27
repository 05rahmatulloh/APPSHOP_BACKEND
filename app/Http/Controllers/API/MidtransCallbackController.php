<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $order = Order::where('order_code', $request->order_id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($request->transaction_status === 'settlement') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'paid_at' => now(),
            ]);

            // 🔥 KURANGI STOK DI SINI
            foreach ($order->items as $item) {
                $item->product->decrement('stock', $item->quantity);
            }
        }

        if (in_array($request->transaction_status, ['expire', 'cancel'])) {
            $order->update([
                'payment_status' => 'failed',
                'status' => 'cancelled',
            ]);
        }

        return response()->json(['message' => 'OK']);
    }
}
