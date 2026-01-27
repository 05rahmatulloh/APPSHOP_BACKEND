@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Manajemen Produk</h4>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        + Tambah Produk
    </a>
</div>

{{-- SUCCESS --}}
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<table class="table table-bordered table-striped align-middle">
    <thead>
        <tr>
            <th width="80">Gambar</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Status</th>
            <th width="160">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
        <tr>
            {{-- IMAGE --}}
            <td class="text-center">
                @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" width="60">
                @else
                -
                @endif
            </td>

            {{-- NAME --}}
            <td>{{ $product->name }}</td>

            {{-- CATEGORY --}}
            <td>{{ $product->category->name }}</td>

            {{-- {{dd($product->rentals->first()->price_per_day)}} --}}
            {{-- PRICE --}}
            <td>
                @if($product->category->type === 'rent')
                @if($product->rentals->first())
                <strong class="text-primary">
                    Rp {{ number_format($product->rentals->first()->price_per_day) }}
                </strong>
                <small class="text-muted d-block">/ hari</small>
                @else
                <span class="text-muted">Belum diset</span>
                @endif
                @else
                Rp {{ number_format($product->price) }}
                @endif
            </td>

            {{-- STATUS --}}
            <td>
                @if($product->category->type === 'rent')
                @if($product->isRentedNow())
                <span class="badge bg-danger">Sedang Disewa</span>
                @else
                <span class="badge bg-success">Tersedia</span>
                @endif
                @else
                <span class="badge bg-info">Produk Jual</span>
                @endif
            </td>

            {{-- ACTION --}}
            <td>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                    Edit
                </a>

                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus produk ini?')">
                        Hapus
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted">
                Belum ada produk
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
