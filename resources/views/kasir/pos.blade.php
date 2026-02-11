@extends('layouts.kasir')

@section('content')
<div class="row">
    <!-- PRODUK -->
    <div class="col-md-8">
        <h5>Kasir POS</h5>
        <div class="row">
            @foreach ($products as $product)
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6>{{ $product->nama }}</h6>
                        <p>Rp {{ number_format($product->harga) }}</p>
                        <small>Stok {{ $product->stok }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- KERANJANG -->
    <div class="col-md-4">
        <h5>Keranjang</h5>
        <div class="card p-3">
            <p>Total: <strong>Rp 0</strong></p>

            <button class="btn btn-success w-100 mb-2">Cash</button>
            <button class="btn btn-secondary w-100 mb-2">QRIS</button>

            <input type="number" class="form-control mb-2" placeholder="Uang bayar">
            <input type="text" class="form-control mb-2" placeholder="Kembalian" readonly>

            <button class="btn btn-primary w-100">
                Simpan & Cetak
            </button>
        </div>
    </div>
</div>
@endsection
