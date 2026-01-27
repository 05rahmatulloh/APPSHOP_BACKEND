<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransService;

class PaymentController extends Controller
{
    public function snapToken($orderId, MidtransService $midtrans)
    {
        $order = Order::where('order_code', $orderId)
            ->where('payment_status', 'pending')
            ->firstOrFail();

        $snapToken = $midtrans->createSnapToken($order);

        return response()->json([
            'snap_token' => $snapToken
        ]);
    }
}
