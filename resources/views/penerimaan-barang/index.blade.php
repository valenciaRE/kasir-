@extends('layouts.app')
@section('content_title', 'Penerimaan Barang')
@section('content')
<div class="card">
    <form action="{{ route('penerimaan-barang.store') }}" method="POST" id="form-penerimaan-barang">
        @csrf
        <input type="hidden" name="user_id" value="{{ auth()->id() }}"> <!-- Petugas Penerima -->

        <div id="data-hidden"></div>
        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
            <h4 class="h5">Penerimaan Barang</h4>
            <div>
                <button type="submit" class="btn btn-primary">Simpan Penerimaan Barang</button>
            </div>
        </div>

        <div class="card-body">
            <div class="w-50">
                <div class="form-group my-1">
                    <label for="">Distributor</label>
                    <input type="text" name="distributor" id="distributor" class="form-control" value="{{ old('distributor') }}">
                    @error('distributor')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group my-1">
                    <label for="">Nomor Faktur</label>
                    <input type="text" name="nomor_faktur" id="nomor_faktur" class="form-control" value="{{ old('nomor_faktur') }}">
                    @error('nomor_faktur')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                <div class="form-group my-1">
                    <label for="">Petugas Penerima</label>
                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                    <small class="text-muted">Otomatis terisi berdasarkan akun login.</small>
</div>
                </div>
            </div>

            <div class="d-flex mt-3">
                <div class="w-100">
                    <label for="">Produk</label>
                    <select name="select2" id="select2" class="form-control"></select>
                </div>
                <div>
                    <label for="">Stok Tersedia</label>
                    <input type="number" id="current_stok" class="form-control mx-1" style="width: 100px" readonly>
                </div>
                <div>
                    <label for="">Harga</label>
                    <input type="number" id="harga_jual" class="form-control mx-1" style="width: 100px" readonly>
                </div>
                <div>
                    <label for="">Qty</label>
                    <input type="number" id="qty" class="form-control mx-1" style="width: 100px" min="1">
                </div>
                <div style="padding-top: 32px;">
                    <button type="button" class="btn btn-dark" id="btn-add">Tambahkan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="card mt-3">
    <div class="card-body">
        <table class="table table-sm" id="table-produk">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Sub Total</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function () {
    let selectedProduk = {};

    // Select2 Produk
    $('#select2').select2({
        theme: 'bootstrap',
        placeholder: 'Cari Produk...',
        ajax: {
            url: "{{ route('get-data.produk') }}",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { search: params.term };
            },
            processResults: function(data) {
                if(data.results){
                    data.results.forEach(item => selectedProduk[item.id] = item);
                    return { results: data.results.map(item => ({
                        id: item.id,
                        text: item.nama_produk,
                        stok: item.stok,
                        harga_jual: item.harga_jual
                    }))};
                }
                return { results: [] };
            },
            cache: true
        },
        minimumInputLength: 1
    });

    // Ambil stok dan harga saat produk dipilih
    $("#select2").on("select2:select", function (e) {
        let data = e.params.data;
        $("#current_stok").val(data.stok || 0);
        $("#harga_jual").val(data.harga_jual || 0);
    });

    // Tambah produk ke tabel
    $("#btn-add").on("click", function () {
        const selectedId  = $("#select2").val();
        const qty         = parseInt($("#qty").val()) || 0;
        const currentStok = parseInt($("#current_stok").val()) || 0;
        const harga       = parseInt($("#harga_jual").val()) || 0;
        const subTotal    = qty * harga;

        if (!selectedId || qty <= 0) {
            alert('Harap pilih produk dan tentukan jumlahnya');
            return;
        }

        if (qty > currentStok) {
            alert('Jumlah barang melebihi stok tersedia');
            return;
        }

        const produk = selectedProduk[selectedId];
        if (!produk) {
            alert('Produk tidak ditemukan');
            return;
        }

        const namaProduk = produk.text;

        let exist = false;
        $('#table-produk tbody tr').each(function () {
            if ($(this).data('id') == selectedId) {
                let currentQty = parseInt($(this).find("td:eq(1)").text());
                let newQty     = currentQty + qty;
                $(this).find("td:eq(1)").text(newQty);
                $(this).find("td:eq(3)").text(newQty * harga);
                exist = true;
                return false;
            }
        });

        if (!exist) {
            const row = `
                <tr data-id="${selectedId}">
                    <td>${namaProduk}</td>
                    <td>${qty}</td>
                    <td>${harga}</td>
                    <td>${subTotal}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm btn-remove">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $("#table-produk tbody").append(row);
        }

        // Reset
        $("#select2").val(null).trigger("change");
        $("#qty").val("");
        $("#current_stok").val("");
        $("#harga_jual").val("");
    });

    // Hapus row
    $("#table-produk").on("click", ".btn-remove", function () {
        $(this).closest('tr').remove();
    });

    // Submit form
    $("#form-penerimaan-barang").on("submit", function () {
        const rows = $("#table-produk tbody tr");
        if(rows.length === 0){
            alert('Tambahkan minimal 1 produk');
            return false;
        }

        $("#data-hidden").html("");
        rows.each(function (index, row) {
            const produkId   = $(row).data("id");
            const namaProduk = $(row).find("td:eq(0)").text();
            const qty        = $(row).find("td:eq(1)").text();
            const harga      = $(row).find("td:eq(2)").text();
            const subTotal   = $(row).find("td:eq(3)").text();

            $("#data-hidden")
                .append(`<input type="hidden" name="produk[${index}][produk_id]" value="${produkId}">`)
                .append(`<input type="hidden" name="produk[${index}][nama_produk]" value="${namaProduk}">`)
                .append(`<input type="hidden" name="produk[${index}][qty]" value="${qty}">`)
                .append(`<input type="hidden" name="produk[${index}][harga_beli]" value="${harga}">`)
                .append(`<input type="hidden" name="produk[${index}][sub_total]" value="${subTotal}">`);
        });
    });
});
</script>
@endpush