@extends('admin.layouts.app')

@section('content')
<h4>Edit Produk</h4>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="hidden" name="category_type" id="category_type">

    {{-- CATEGORY --}}
    <div class="mb-3">
        <label>Kategori</label>
        <select name="category_id" id="category" class="form-control">
            @foreach ($categories as $category)
            <option value="{{ $category->id }}" data-type="{{ $category->type }}" {{ $product->category_id ==
                $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- NAME --}}
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}">
    </div>

    {{-- DESCRIPTION --}}
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control">{{ $product->description }}</textarea>
    </div>

    {{-- IMAGE --}}
    <div class="mb-3">
        <label>Gambar</label><br>
        @if($product->image)
        <img src="{{ asset('storage/'.$product->image) }}" width="80"><br>
        @endif
        <input type="file" name="image" class="form-control">
    </div>

    {{-- SALE --}}
    <div id="sale-fields" style="display:none">
        <hr>
        <h6>Produk Jual</h6>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="price" class="form-control" value="{{ $product->price }}">
        </div>

        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}">
        </div>
    </div>

    {{-- RENT --}}
    <div id="rent-fields" style="display:none">
        <hr>
        <h6>Produk Sewa</h6>

        <input type="date" name="start_date" class="form-control mb-2" value="{{ optional($rental)->start_date }}">

        <input type="date" name="end_date" class="form-control mb-2" value="{{ optional($rental)->end_date }}">

        <input type="number" name="price_per_day" class="form-control mb-2"
            value="{{ optional($rental)->price_per_day }}">

        <input type="number" name="deposit" class="form-control mb-2" value="{{ optional($rental)->deposit }}">

        <select name="rental_status" class="form-control">
            <option value="active" {{ optional($rental)->status == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="finished" {{ optional($rental)->status == 'finished' ? 'selected' : '' }}>Selesai</option>
            <option value="cancelled" {{ optional($rental)->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan
            </option>
        </select>
    </div>

    <button class="btn btn-success">Update</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
</form>

<script>
    const category = document.getElementById('category');
const sale = document.getElementById('sale-fields');
const rent = document.getElementById('rent-fields');
const categoryType = document.getElementById('category_type');

function toggleFields() {
    const type = category.options[category.selectedIndex].dataset.type;
    sale.style.display = type === 'sale' ? 'block' : 'none';
    rent.style.display = type === 'rent' ? 'block' : 'none';
    categoryType.value = type;
}

category.addEventListener('change', toggleFields);
toggleFields();
</script>
@endsection
