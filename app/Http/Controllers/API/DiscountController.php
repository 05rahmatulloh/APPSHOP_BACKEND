<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Discounts;
use App\Services\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

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

    $result = $discountService->apply($product, $request->code);

    return response()->json([
        'success' => true,
        'data' => [

            'original_price' => $result['original_price'],
            'final_price'    => $result['final_price'],
            'discount_type'  => $result['discount_type'],
            'discount_value' => $result['discount_value'],
            'free_shipping'  => $result['free_shipping'],
            'is_discounted'  => $result['final_price'] < $result['original_price'],
        ]
    ]);
}


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:discounts,code',
            'scope' => 'required|in:product,order',
            'type' => 'in:percentage,nominal,free_shipping',
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

        // ===============================
        // VALIDASI LOGIKA BISNIS
        // ===============================
        if ($request->scope === 'product' && empty($request->product_ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Diskon produk wajib memilih produk'
            ], 422);
        }

        if ($request->scope === 'order' && $request->type?$request->type:'free_shipping' !== 'free_shipping') {
            return response()->json([
                'success' => false,
                'message' => 'Diskon order hanya boleh free shipping'
            ], 422);
        }

        DB::transaction(function () use ($request, &$discount) {
            $discount = Discounts::create([
                'code' => $request->code,
                'scope' => $request->scope,
                'type' => $request->type?$request->type:'free_shipping',
                'value' => $request->value ?? 0,
                'stock' => $request->stock,
                'is_active' => $request->is_active,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            // HANYA diskon produk yang punya relasi produk
            if ($request->scope === 'product') {
                $discount->products()->attach($request->product_ids);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Diskon berhasil dibuat',
            'data' => $discount
        ], 201);
    }
}
