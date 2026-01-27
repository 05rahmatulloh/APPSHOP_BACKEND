@extends('layouts.admin')

@section('title', 'Detail Produk')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Detail Produk</h5>
    </div>

    <div class="card-body">
        <div class="row">

            {{-- GAMBAR --}}
            <div class="col-md-4">
                @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" class="img-fluid rounded">
                @else
                <div class="text-muted">Tidak ada gambar</div>
                @endif
            </div>

            {{-- DETAIL PRODUK --}}
            <div class="col-md-8">
                <table class="table table-bordered">

                    <tr>
                        <th>Nama Produk</th>
                        <td>{{ $product->name }}</td>
                    </tr>

                    <tr>
                        <th>Kategori</th>
                        <td>{{ $product->category->name }}</td>
                    </tr>

                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $product->description ?? '-' }}</td>
                    </tr>

                    {{-- PRODUK JUAL --}}
                    @if($product->category->type === 'sale')
                    <tr>
                        <th>Harga</th>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    </tr>

                    <tr>
                        <th>Stok</th>
                        <td>{{ $product->stock }}</td>
                    </tr>
                    @endif

                    {{-- PRODUK SEWA --}}
                    @if($product->category->type === 'rent')
                    @php
                    $rental = $product->rentals->last();
                    @endphp

                    <tr>
                        <th>Status</th>
                        <td>
                            @if($product->isCurrentlyRented())
                            <span class="badge bg-danger">Sedang Disewa</span>
                            @else
                            <span class="badge bg-success">Tersedia</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Periode Sewa</th>
                        <td>
                            {{ optional($rental)->start_date }}
                            s/d
                            {{ optional($rental)->end_date }}
                        </td>
                    </tr>

                    <tr>
                        <th>Harga / Hari</th>
                        <td>
                            Rp {{ number_format(optional($rental)->price_per_day, 0, ',', '.') }}
                        </td>
                    </tr>

                    <tr>
                        <th>Deposit</th>
                        <td>
                            Rp {{ number_format(optional($rental)->deposit, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endif

                </table>

                {{-- ACTION --}}
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    Kembali
                </a>

                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">
                    Edit
                </a>

            </div>
        </div>
    </div>
</div>
@endsection
