<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends Controller
{
    /**
     * Ambil cart user (beserta item & produk)
     * GET /api/cart
     */
    public function index(Request $request, CartService $service)
    {



//    return $request->user();
        $cart = $service->getOrCreateCart($request->user());

        return response()->json(
            $cart->load('items.product')
        );
    }

    /**
     * Tambah item ke cart
     * POST /api/cart
     */
    public function store(Request $request, CartService $service)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        $cart = $service->addItem(
            $request->user(),
            $validated['product_id'],
            $validated['quantity']
        );

        return response()->json($cart);
    }

    /**
     * Update quantity item cart
     * PUT /api/cart/{item}
     */
    public function update(Request $request, int $itemId, CartService $service)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer'],
        ]);

        $service->updateQty(
            $request->user(),
            $itemId,
            $validated['quantity']
        );

        return response()->json([
            'message' => 'Cart item updated'
        ]);
    }

    /**
     * Hapus semua item di cart
     * DELETE /api/cart
     */
    public function clear(Request $request, CartService $service)
    {
        $service->clearCart($request->user());

        return response()->json([
            'message' => 'Cart cleared'
        ]);
    }
}
