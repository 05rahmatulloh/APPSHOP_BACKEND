<?php

namespace App\Http\Controllers\ProductManajement;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
public function index()
{
$categories = Category::latest()->get();
return view('admin.categories.index', compact('categories'));
}

public function create()
{
return view('admin.categories.create');
}

public function store(Request $request)
{
Validator::make($request->all(), [
'name' => 'required|string|max:255',
'type' => 'required|in:sale,rent',
])->validate();

Category::create([
'name' => $request->name,
'slug' => Str::slug($request->name),
'type' => $request->type,
]);

return redirect()->route('categories.index')->with('success', 'Category berhasil dibuat');
}
}
