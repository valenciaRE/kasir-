@extends('layouts.app')
@section('content_title', 'Laporan Penerimaan Barang')
@section('content')

<div class="card">
    <div class="d-flex justify-content-between align-items-center p-3">
        <div>
            <h3 class="h3">PT POS APP</h3>
            <h4 class="h6">Laporan Penerimaan Barang</h4>
        </div>
        <div>
            <small>{{ $data->tanggal_penerimaan }}</small>
        </div>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <div class="col-4">
                <div class="d-flex align-content-center mb-2">
                    <h6 class="text-bold w-25">Distributor</h6>
                    <p class="mb-0">{{ $data->distributor }}</p>
                </div>
                <div class="d-flex align-content-center">
                    <h6 class="text-bold w-25">Nomor Faktur</h6>
                    <p class="mb-0">{{ $data->nomor_faktur }}</p>
                </div>
            </div>

            <div class="col-4">
                <div class="d-flex align-content-center">
                    <h6 class="text-bold w-25">Nama Petugas Penerima</h6>
                    <p class="mb-0">{{ $data->petugas_penerimaan }}</p>
                </div>
            </div>
        </div>

        <!-- ✅ TAMBAHAN WRAPPER BIAR TABLE FULL & RAPI -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="table1" class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 20px">No</th>
                                <th>Nama Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1}}</td>
                                <td>{{ $item->nama_produk }}</td>
                                <td>{{ number_format($item->qty) }} <small>pcs</small></td>
                                <td>Rp. {{ number_format($item->harga_beli )}}</td>
                                <td>Rp. {{ number_format($item->sub_total )}}</td>
                            </tr>
                            @endforeach
                           <!-- ✅ INI KUNCI BIAR SAMA VIDEO -->
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-bold text-right">Total Pembelian</td>
                                <td class="text-bold">Rp. {{ number_format($data->total) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- =============================== -->

    </div>
</div>

@endsection
