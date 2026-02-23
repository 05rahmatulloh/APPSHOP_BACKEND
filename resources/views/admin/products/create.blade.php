@extends('admin.layouts.app')

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
        <textarea name="description" class="form-control" required></textarea>
    </div>

    {{-- IMAGE --}}
    <div class="mb-3">
        <label>Gambar Produk</label>
        <input type="file" name="image" class="form-control" required>
    </div>

    {{-- COD --}}
    <div class="mb-3">
        <label>COD Available?</label>
        <select name="is_cod_available" class="form-control" required>
            <option value="1">Ya</option>
            <option value="0">Tidak</option>
        </select>
    </div>

    {{-- MIDTRANS --}}
    <div class="mb-3">
        <label>Midtrans Available?</label>
        <select name="is_midtrans_available" class="form-control" required>
            <option value="1">Ya</option>
            <option value="0">Tidak</option>
        </select>
    </div>

    {{-- ================= SALE ================= --}}
    <div id="sale-fields" style="display:none">
        <hr>
        <h6>Produk Jual</h6>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="price" id="price" class="form-control" min="0" disabled>
        </div>

        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stock" id="stock" class="form-control" min="0" disabled>
        </div>
    </div>

    {{-- ================= RENT ================= --}}
    <div id="rent-fields" style="display:none">
        <hr>
        <h6>Produk Sewa</h6>

        <div class="mb-3">
            <label>Harga / Hari</label>
            <input type="number" name="price_per_day" id="price_per_day" class="form-control" min="0" disabled>
        </div>

        <div class="mb-3">
            <label>Deposit (Opsional)</label>
            <input type="number" name="deposit" class="form-control" min="0" disabled>
        </div>

        <small class="text-muted">
            * Tanggal sewa akan diatur saat ada penyewa
        </small>
    </div>

    <button type="submit" id="submitBtn" class="btn btn-success" disabled>Simpan</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
</form>

<script>
    const category = document.getElementById('category');
    const sale = document.getElementById('sale-fields');
    const rent = document.getElementById('rent-fields');
    const submitBtn = document.getElementById('submitBtn');

    const price = document.getElementById('price');
    const stock = document.getElementById('stock');
    const pricePerDay = document.getElementById('price_per_day');

    function toggleFields() {
        const type = category.options[category.selectedIndex]?.dataset.type;

        // reset
        sale.style.display = 'none';
        rent.style.display = 'none';

        price.disabled = true;
        stock.disabled = true;
        pricePerDay.disabled = true;

        price.required = false;
        stock.required = false;
        pricePerDay.required = false;

        submitBtn.disabled = !type;

        if (type === 'sale') {
            sale.style.display = 'block';
            price.disabled = false;
            stock.disabled = false;
            price.required = true;
            stock.required = true;
        }

        if (type === 'rent') {
            rent.style.display = 'block';
            pricePerDay.disabled = false;
            pricePerDay.required = true;
        }
    }

    category.addEventListener('change', toggleFields);
</script>
@endsection
