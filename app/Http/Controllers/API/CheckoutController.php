<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function store(Request $request, CheckoutService $checkoutService)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'payment_method'   => 'required|in:cod,transfer,midtrans',
            'code_discount'   => 'nullable|string',
        ]);

        try {
            $order = $checkoutService->checkout(
                $request->user(),
                $validated
            );

            return response()->json([
                'message' => 'Checkout berhasil',
                'data'    => $order,
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }




public function preview(Request $request, CheckoutService $checkoutService)
{
    $validated = $request->validate([
        'shipping_address' => 'required|string',
        'discount_code' => 'nullable|string',
        'shipping_cost' => 'nullable|numeric|min:0',
    ]);

    $data = $checkoutService->preview(
        $request->user(),
        $validated
    );

    return response()->json([
        'message' => 'Preview checkout',
        'data' => $data,
    ]);
}





}
