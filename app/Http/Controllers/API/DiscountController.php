<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\DiscountService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\discounts;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{



public function create()
{
return view('admin.discounts.create', [
'products' => Product::all()
]);
}


    public function applyDiscount(
        Request $request,
        Product $product,
        DiscountService $discountService
    ) {
        $request->validate([
            'code' => 'required|string'
        ]);

        try {
            $result = $discountService->apply($product, $request->code);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function store(Request $request)
    {
$validator = Validator::make($request->all(), [
'code' => 'required|string|unique:discounts,code',
'type' => 'required|in:percentage,nominal,free_shipping',
'value' => 'nullable|numeric|min:0',
'stock' => 'required|integer|min:0',
'is_active' => 'required|boolean',
'start_date' => 'nullable|date',
'end_date' => 'nullable|date|after_or_equal:start_date',
'product_ids' => 'array',
'product_ids.*' => 'exists:products,id',
]);

if ($validator->fails()) {
return response()->json([
'success' => false,
'errors' => $validator->errors()
], 422);
}


    DB::transaction(function () use ($request, &$discount) {
    $discount = Discounts::create([
    'code' => $request->code,
    'type' => $request->type,
    'value' => $request->value ?? 0,
    'stock' => $request->stock,
    'is_active' => $request->is_active,
    'start_date' => $request->start_date,
    'end_date' => $request->end_date,
    ]);

    // attach produk ke diskon
    $discount->products()->attach($request->product_ids);
    });

    return response()->json([
    'success' => true,
    'message' => 'Diskon berhasil dibuat',
    'data' => $discount
    ], 201);
    }

}
