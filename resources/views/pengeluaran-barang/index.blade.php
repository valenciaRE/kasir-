@extends('layouts.app')

@section('content_title', 'Pengeluaran Barang / Transaksi')

@section('content')
<div class="card">

    <x-alert :errors="$errors" />

    <form action="{{ route('pengeluaran-barang.store') }}" method="POST" id="form-pengeluaran-barang">
        @csrf

        <div id="data-hidden"></div>

        <div class="card-body">

            {{-- INPUT PRODUK --}}
            <div class="row align-items-end mb-4">
                <div class="col-md-4">
                    <label class="font-weight-bold">Produk</label>
                    <select id="select2" class="form-control"></select>
                </div>

                <div class="col-md-2">
                    <label class="font-weight-bold">Stok</label>
                    <input type="number" id="current_stok" class="form-control bg-light" readonly>
                </div>

                <div class="col-md-2">
                    <label class="font-weight-bold">Harga</label>
                    <input type="number" id="harga_jual" class="form-control bg-light" readonly>
                </div>

                <div class="col-md-2">
                    <label class="font-weight-bold">Qty</label>
                    <input type="number" id="qty" class="form-control" min="1">
                </div>

                <div class="col-md-2">
                    <button type="button" id="btn-tambah" class="btn btn-dark w-100">
                        Tambahkan
                    </button>
                </div>
            </div>

            {{-- TABLE + TOTAL --}}
            <div class="row">
                <div class="col-md-9">
                    <table class="table table-bordered" id="table-produk">
                        <thead class="bg-light">
                            <tr>
                                <th>Nama Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Sub Total</th>
                                <th class="text-center">Opsi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">

                            <div class="form-group">
                                <label>Total</label>
                                <input type="text" id="total_display"
                                    class="form-control text-right font-weight-bold"
                                    readonly value="0">
                                <input type="hidden" id="total_raw"
                                    name="total_keseluruhan" value="0">
                            </div>

                            <div class="form-group">
                                <label>Kembalian / Selisih</label>
                                <input type="text" id="kembalian_display"
                                    class="form-control text-right"
                                    readonly value="0">
                            </div>

                            <div class="form-group">
                                <label>Jumlah Bayar</label>
                                <input type="number" id="bayar"
                                    name="bayar"
                                    class="form-control text-right">
                            </div>

                            <button type="submit"
                                class="btn btn-primary w-100"
                                id="btn-simpan"
                                disabled>
                                Simpan Transaksi
                            </button>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('script')
<script>
$(function () {

    let produkCache = {};

    // =====================
    // SELECT2 PRODUK
    // =====================
    $('#select2').select2({
        theme: 'bootstrap',
        placeholder: 'Cari Produk...',
        ajax: {
            url: "{{ route('get-data.produk') }}",
            dataType: 'json',
            processResults: function (data) {
                data.forEach(p => produkCache[p.id] = p);
                return {
                    results: data.map(p => ({
                        id: p.id,
                        text: p.nama_produk
                    }))
                };
            }
        }
    });

    $('#select2').on('select2:select', function (e) {
        let id = e.params.data.id;

        $.get("{{ route('get-data.cek-stok') }}", { id }, res => {
            $('#current_stok').val(res);
        });

        $.get("{{ route('get-data.cek-harga') }}", { id }, res => {
            $('#harga_jual').val(res);
        });
    });

    // =====================
    // TAMBAH PRODUK
    // =====================
    $('#btn-tambah').click(function () {

        let id    = $('#select2').val();
        let qty   = parseInt($('#qty').val()) || 0;
        let harga = parseInt($('#harga_jual').val()) || 0;
        let stok  = parseInt($('#current_stok').val()) || 0;

        if (!id) return alert('Pilih produk!');
        if (qty <= 0) return alert('Qty tidak valid!');
        if (qty > stok) return alert('Stok tidak cukup!');

        let nama = produkCache[id].nama_produk;
        let sub  = qty * harga;

        let row = $('#table-produk tbody').find(`tr[data-id="${id}"]`);

        if (row.length) {
            let qtyLama = parseInt(row.find('.col-qty').text());
            let qtyBaru = qtyLama + qty;
            let subBaru = qtyBaru * harga;

            row.find('.col-qty').text(qtyBaru);
            row.find('.col-sub').text(subBaru);
            row.attr('data-subtotal', subBaru);
        } else {
            $('#table-produk tbody').append(`
                <tr data-id="${id}" data-harga="${harga}" data-subtotal="${sub}">
                    <td>${nama}</td>
                    <td class="text-center col-qty">${qty}</td>
                    <td class="text-right">${harga}</td>
                    <td class="text-right col-sub">${sub}</td>
                    <td class="text-center">
                        <button type="button"
                            class="btn btn-danger btn-sm btn-remove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        }

        hitungTotal();

        $('#select2').val(null).trigger('change');
        $('#qty').val('');
        $('#current_stok').val('');
        $('#harga_jual').val('');
    });

    // =====================
    // HITUNG TOTAL & SELISIH
    // =====================
    function hitungTotal() {
        let total = 0;
        $('#table-produk tbody tr').each(function () {
            total += parseInt($(this).data('subtotal'));
        });

        $('#total_display').val(total);
        $('#total_raw').val(total);

        hitungKembalian();
    }

    function hitungKembalian() {
        let total = parseInt($('#total_raw').val()) || 0;
        let bayar = parseInt($('#bayar').val()) || 0;

        let selisih = bayar - total; // BISA MINUS
        $('#kembalian_display').val(selisih);

        // warna
        if (selisih < 0) {
            $('#kembalian_display')
                .removeClass('text-success')
                .addClass('text-danger');
        } else {
            $('#kembalian_display')
                .removeClass('text-danger')
                .addClass('text-success');
        }

        // kunci simpan kalau bayar kurang
        $('#btn-simpan').prop('disabled', bayar < total);
    }

    $('#bayar').on('input', hitungKembalian);

    // =====================
    // HAPUS BARIS
    // =====================
    $('#table-produk').on('click', '.btn-remove', function () {
        $(this).closest('tr').remove();
        hitungTotal();
    });

    // =====================
    // SUBMIT
    // =====================
    $('#form-pengeluaran-barang').submit(function (e) {

        let total = parseInt($('#total_raw').val()) || 0;
        let bayar = parseInt($('#bayar').val()) || 0;

        if ($('#table-produk tbody tr').length === 0) {
            e.preventDefault();
            alert('Produk masih kosong!');
            return;
        }

        if (bayar < total) {
            e.preventDefault();
            alert('Jumlah bayar masih kurang!');
            return;
        }

        let hidden = '';

        $('#table-produk tbody tr').each(function (i, row) {
           hidden += `
            <input type="hidden" name="produk[${i}][nama_produk]" value="${$(row).find('td:first').text()}">
            <input type="hidden" name="produk[${i}][qty]" value="${$(row).find('.col-qty').text()}">
            <input type="hidden" name="produk[${i}][produk_id]" value="${$(row).data('id')}">
            <input type="hidden" name="produk[${i}][harga]" value="${$(row).data('harga')}">
            <input type="hidden" name="produk[${i}][sub_total]" value="${$(row).data('subtotal')}">
        `;

        });

        $('#data-hidden').html(hidden);
    });

});
</script>
@endpush
