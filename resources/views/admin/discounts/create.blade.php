@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow mt-10">

    <h1 class="text-2xl font-bold mb-6">Tambah Diskon</h1>

    {{-- Error Validation --}}
    @if ($errors->any())
    <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('discounts.store') }}" method="POST">
        @csrf

        {{-- Kode Diskon --}}
        <div class="mb-4">
            <label class="block font-medium mb-1">Kode Diskon</label>
            <input type="text" name="code" class="w-full border rounded px-3 py-2" required>
        </div>

        {{-- Scope --}}
        <div class="mb-4">
            <label class="block font-medium mb-1">Jenis Diskon</label>
            <select name="scope" id="discountScope" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Pilih --</option>
                <option value="product">Diskon Produk</option>
                <option value="order">Gratis Ongkir</option>
            </select>
        </div>

        {{-- Tipe Diskon --}}
        <div class="mb-4" id="typeWrapper">
            <label class="block font-medium mb-1">Tipe Diskon</label>
            <select name="type" id="discountType" class="w-full border rounded px-3 py-2">
                <option value="">-- Pilih --</option>
                <option value="percentage">Persentase (%)</option>
                <option value="nominal">Nominal (Rp)</option>
                <option value="free_shipping">Gratis Ongkir</option>
            </select>
        </div>

        {{-- Nilai --}}
        <div class="mb-4" id="valueWrapper">
            <label class="block font-medium mb-1">Nilai Diskon</label>
            <input type="number" name="value" id="discountValue" class="w-full border rounded px-3 py-2">
        </div>

        {{-- Stok --}}
        <div class="mb-4">
            <label class="block font-medium mb-1">Stok</label>
            <input type="number" name="stock" value="0" min="0" class="w-full border rounded px-3 py-2" required>
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <label class="block font-medium mb-1">Status</label>
            <div class="flex gap-6">
                <label><input type="radio" name="is_active" value="1" checked> Aktif</label>
                <label><input type="radio" name="is_active" value="0"> Nonaktif</label>
            </div>
        </div>

        {{-- Periode --}}
        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">Start Date</label>
                <input type="date" name="start_date" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-medium mb-1">End Date</label>
                <input type="date" name="end_date" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        {{-- Produk --}}
        <div class="mb-6" id="productWrapper">
            <label class="block font-medium mb-2">Produk yang Mendapat Diskon</label>
            <div class="border rounded p-3 max-h-48 overflow-y-auto">
                @foreach ($products as $product)
                <label class="flex items-center gap-2 mb-2">
                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}">
                    {{ $product->name }}
                </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Simpan Diskon
        </button>
    </form>
</div>

{{-- JS --}}
<script>
    const scope = document.getElementById('discountScope');
const type = document.getElementById('discountType');
const value = document.getElementById('discountValue');
const productWrapper = document.getElementById('productWrapper');

scope.addEventListener('change', function () {
    if (this.value === 'order') {
        type.value = 'free_shipping';
        type.setAttribute('disabled', true);

        value.value = 0;
        value.setAttribute('disabled', true);

        productWrapper.style.display = 'none';
    } else {
        type.removeAttribute('disabled');
        value.removeAttribute('disabled');
        productWrapper.style.display = 'block';
    }
});
</script>
@endsection
