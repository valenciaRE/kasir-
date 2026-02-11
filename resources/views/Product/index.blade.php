@extends('layouts.app')
@section('content_title', 'Data Product')
@section('content')

<div class="card">
    <div class="card-title">
        <h4 class="card-header">Data Product</h4>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-end mb-2">
            <x-product.form-product/>
        </div>
        <x-alert :errors="$errors" type="warning" />
        <table class="table table-sm" id="table2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Harga Jual</th>
                    <th>Harga Beli</th>
                    <th>Stok</th>
                    <th>Aktif</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->name_product }}</td>
                        <td>Rp. {{ number_format($product->harga_jual) }}</td>
                        <td>Rp. {{ number_format($product->harga_beli_pokok) }}</td>
                        <td>{{ number_format($product->stok) }}</td>
                        <td>
                            <p class="badge {{ $product->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </p>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <x-product.form-product :id="$product->id" />
                                <form action="{{ route('master-data.product.destroy', $product->id) }}"  method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus produk {{ $product->name_product }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-danger mx-1 border-0 bg-transparent">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection