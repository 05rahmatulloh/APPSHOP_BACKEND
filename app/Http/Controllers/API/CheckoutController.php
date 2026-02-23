<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * =====================
     * CHECKOUT FINAL
     * =====================
     */
    public function store(Request $request, CheckoutService $checkoutService)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'payment_method'   => 'required|in:cod,transfer,midtrans',
            'discount_code'    => 'nullable|string',
        ]);

        $result = $checkoutService->checkout(
            $request->user(),
            $validated
        );



        
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
            ], 400);
        }

        return response()->json([
            'message' => 'Checkout berhasil',
            'data'    => $result['order'],
        ], 201);
    }

    /**
     * =====================
     * PREVIEW CHECKOUT
     * =====================
     */
    public function preview(Request $request, CheckoutService $checkoutService)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'discount_code'    => 'nullable|string',
        ]);

        $result = $checkoutService->preview(
            $request->user(),
            $validated
        );

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
            ], 400);
        }

        return response()->json([
            'message' => 'Preview checkout',
            'data'    => $result,
        ]);
    }
}
