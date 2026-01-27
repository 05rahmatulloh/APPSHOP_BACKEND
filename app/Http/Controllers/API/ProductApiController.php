<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    /**
     * GET /api/products
     * List semua produk
     */
    public function index()
    {
        $products = Product::with(['category', 'rentals'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * GET /api/products/{id}
     * Detail produk
     */
    public function show($id)
    {
        $product = Product::with(['category', 'rentals'])
            ->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }
}
