<?php

namespace App\Http\Controllers\ProductManajement;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /* ===================== INDEX ===================== */
    public function index()
    {
        $products = Product::with(['category', 'rentals'])
            ->latest()
            ->get();

        return view('admin.products.index', compact('products'));
    }

    /* ===================== CREATE ===================== */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /* ===================== STORE ===================== */
    public function store(Request $request)
    {
        $category = Category::findOrFail($request->category_id);

        // ---------- Validation Rules ----------
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_cod_available' => 'required|boolean',
        ];

        if ($category->type === 'sale') {
            $rules['price'] = 'required|numeric|min:0';
            $rules['stock'] = 'required|integer|min:0';
        }

        if ($category->type === 'rent') {
            $rules['start_date']    = 'nullable|date';
            $rules['end_date']      = 'nullable|date|after_or_equal:start_date';
            $rules['price_per_day'] = 'required|numeric|min:0';
            $rules['deposit']       = 'nullable|numeric|min:0';
        }

        Validator::make($request->all(), $rules)->validate();

        // ---------- Image Upload ----------
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // ---------- Create Product ----------
      $product = Product::create([
      'category_id' => $category->id,
      'name' => $request->name,
      'description' => $request->description,
      'price' => $request->price ?? 0,
      'stock' => $request->stock ?? 0,
      'is_cod_available' => $request->is_cod_available ?? true,
      'image' => $imagePath,
      ]);


        // ---------- Rental ----------
        if ($category->type === 'rent') {
            Rental::create([
                'product_id'    => $product->id,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'price_per_day' => $request->price_per_day,
                'deposit'       => $request->deposit,
                'status'        => 'active',
            ]);
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    /* ===================== EDIT ===================== */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $rental = $product->rentals()->latest()->first();

        return view('admin.products.edit', compact('product', 'categories', 'rental'));
    }

    /* ===================== UPDATE ===================== */
    public function update(Request $request, Product $product)
    {
        $category = Category::findOrFail($request->category_id);

        // ---------- Validation Rules ----------
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        if ($category->type === 'sale') {
            $rules['price'] = 'required|numeric|min:0';
            $rules['stock'] = 'required|integer|min:0';
        }

        if ($category->type === 'rent') {
            $rules['start_date']    = 'nullable|date';
            $rules['end_date']      = 'nullable|date|after_or_equal:start_date';
            $rules['price_per_day'] = 'required|numeric|min:0';
            $rules['deposit']       = 'nullable|numeric|min:0';
        }

        Validator::make($request->all(), $rules)->validate();

        // ---------- Image Update ----------
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        // ---------- Update Product ----------
        $product->update([
        'category_id' => $category->id,
        'name' => $request->name,
        'description' => $request->description,
        'price' => $category->type === 'sale' ? $request->price : 0,
        'stock' => $category->type === 'sale' ? $request->stock : 0,
        'is_cod_available' => $request->is_cod_available ?? true,
        ]);


        // ---------- Update Rental ----------
        if ($category->type === 'rent') {
            $rental = $product->rentals()->latest()->first();

            if ($rental) {
                $rental->update([
                    'start_date'    => $request->start_date,
                    'end_date'      => $request->end_date,
                    'price_per_day' => $request->price_per_day,
                    'deposit'       => $request->deposit,
                ]);
            } else {
                Rental::create([
                    'product_id'    => $product->id,
                    'start_date'    => $request->start_date,
                    'end_date'      => $request->end_date,
                    'price_per_day' => $request->price_per_day,
                    'deposit'       => $request->deposit,
                    'status'        => 'active',
                ]);
            }
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    /* ===================== DESTROY ===================== */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}
