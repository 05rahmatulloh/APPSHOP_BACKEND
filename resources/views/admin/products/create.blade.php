@extends('admin.layouts.admin')

@section('content')
<h4 class="mb-3">Tambah Produk</h4>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- CATEGORY --}}
    <div class="mb-3">
        <label>Kategori</label>
        <select name="category_id" id="category" class="form-control" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach ($categories as $category)
            <option value="{{ $category->id }}" data-type="{{ $category->type }}">
                {{ $category->name }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- NAME --}}
    <div class="mb-3">
        <label>Nama Produk</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    {{-- DESCRIPTION --}}
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    {{-- IMAGE --}}
    <div class="mb-3">
        <label>Gambar</label>
        <input type="file" name="image" class="form-control">
    </div>

    {{-- ================= SALE ================= --}}
    <div id="sale-fields" style="display:none">
        <hr>
        <h6>Produk Jual</h6>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="price" class="form-control">
        </div>

        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stock" class="form-control">
        </div>
    </div>

    {{-- ================= RENT ================= --}}
    <div id="rent-fields" style="display:none">
        <hr>
        <h6>Produk Sewa</h6>

        <div class="mb-3">
            <label>Harga / Hari</label>
            <input type="number" name="price_per_day" class="form-control">
        </div>

        <div class="mb-3">
            <label>Deposit (Opsional)</label>
            <input type="number" name="deposit" class="form-control">
        </div>

        <small class="text-muted">
            * Tanggal sewa akan diisi saat ada penyewa
        </small>
    </div>

    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
</form>

<script>
    const category = document.getElementById('category');
    const sale = document.getElementById('sale-fields');
    const rent = document.getElementById('rent-fields');

    function toggleFields() {
        const type = category.options[category.selectedIndex]?.dataset.type;
        sale.style.display = type === 'sale' ? 'block' : 'none';
        rent.style.display = type === 'rent' ? 'block' : 'none';
    }

    category.addEventListener('change', toggleFields);
</script>
@endsection
